<div ng-app="fob_entity_edit">
    <div ng-controller="FormBuilderForm">
        <form>
            <input type="hidden" ng-model="entity.fid" />
            <input type="hidden" ng-model="entity.status" />

            <div class="form-item form-type-textfield form-item-title">
                <label for="edit-title">Title <span class="form-required" title="This field is required.">*</span></label>
                <input type="text"
                       id="edit-title"
                       name="title"
                       size="60"
                       maxlength="255"
                       class="form-text required"
                       ng-model="entity.title"
                       ng-change="slugAuto()" />

                <span class="field-suffix" ng-if="slugDoAuto">
                    <small id="edit-name-machine-name-suffix" style="display: inline;">
                        <strong class="machine-name-label">Slug:</strong>
                        <span class="machine-name-value">{{entity.slug}}</span>
                        <span class="admin-link">
                            <a href ng-click="slugDisableAuto()">Edit</a>
                        </span>
                    </small>
                </span>
            </div>

            <div class="form-item fomr-type-textfield form-item-slug" ng-if="!slugDoAuto">
                <label for="edit-title">Slug <span class="form-required" title="This field is required.">*</span></label>

                <input type="text"
                       id="edit-slug"
                       name="slug"
                       size="60"
                       maxlength="255"
                       class="form-text required"
                       ng-model="entity.slug" />
            </div>

            <div id="formBuilderFields">
                <div id="availableResources">
                    <div id="availableEntityTypes" class="form-item form-type-checkboxes form-item-entityTypes">
                        <label for="entityTypes">Entity types</label>
                        <div id="edit-entityTypes" class="form-checkboxes">
                            <div class="form-item form-type-checkbox" ng-repeat="(machineName, entityType) in available.entityTypes">
                                <input
                                    type="checkbox"
                                    id="edit-entityType-{{machineName}}"
                                    name="entityTypes[{{machineName}}]"
                                    value="{{machineName}}"
                                    class="form-checkbox"
                                    ng-model="entity.entityTypes[machineName]"
                                    ng-click="toggleEntityType(machineName)"
                                    ng-disabled="available.addingEntityTypeNames[machineName]" />

                                <label class="option" for="edit-entityType-{{machineName}}">
                                    {{entityType.humanName}}
                                    <span class="adding" ng-if="available.addingEntityTypeNames[machineName]">adding…</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="availableFields" class="form-item form-type-markup">
                        <label for="entityTypes">Available Fields</label>
                        <div class="item-list">
                            <ul>
                                <li class="empty" ng-show="isAvailableFieldsEmpty()">
                                    There is no available fields.
                                </li>
                                <li data-name="{{name}}"
                                    ng-repeat="(name, field) in available.fields"
                                    ui-draggable="true"
                                    drag="name">
                                    <strong class="field-human-name">{{field.humanName}}</strong>
                                    <span class="entity-type-name">({{field.entityTypeName}})</span>
                                    <div class="description" ng-if="field.description">
                                        {{field.description}}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="formFields" class="form-item form-type-markup">
                    <label>Form fields</label>

                    <div class="item-list">
                        <ul>
                            <li class="empty" ng-show="isFieldsEmpty()">
                                Empty.
                            </li>

                            <li class="field"
                                ng-repeat="field in uiFormFields| orderBy:'weight'"
                                ui-on-Drop="fieldOnDrop($event, $data, field.uuid)"
                                ui-draggable="true"
                                drag="field.uuid">
                                <strong class="field-human-name">{{field.humanName}}</strong>
                                <span class="entity-type-name">({{field.entityTypeName}})</span>

                                <div class="field-actions">
                                    <a href ng-click="fieldConfig(field.uuid)">Config</a>
                                    <a href ng-click="fieldRemove(field.uuid)">Remove</a>
                                </div>
                            </li>
                            <li class="adding" ng-repeat="field in available.addingFields">
                                Adding <strong>{{field.humanName}}</strong>…
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="break"></div>

            <div class="form-item form-type-checkbox form-item-status">
                <input type="checkbox"
                       id="edit-status"
                       name="status"
                       class="form-checkbox"
                       ng-model="entity.status" />
                <label class="option" for="edit-status">Published</label>
            </div>

            <div id="edit-language" class="form-radios">
                <label for="edit-title">Language</label>
                <div class="form-item form-type-radio form-item-language" ng-repeat="item in available.languages| orderBy: weight">
                    <input type="radio"
                           name="language"
                           id="edit-language-{{item.language}}"
                           value="{{item.language}}"
                           checked="checked"
                           ng-model="entity.language"
                           class="form-radio">
                    <label class="option" for="edit-language-{{item.language}}">{{item.name}}</label>
                </div>
            </div>

            <div class="form-actions form-wrapper" id="edit-actions">
                <button id="edit-submit" class="form-submit" ng-click="submit()">
                    <span ng-if="!saving">Save</span>
                    <span ng-if="saving">Saving…</span>
                </button>
            </div>
        </form>

    </div>
</div>
