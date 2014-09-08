<div ng-app="fob_entity_edit">
    <div ng-controller="HelloCtrl">
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
                       ng-model="entity.title" />
            </div>

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

            <div class="form-item form-type-checkboxes form-item-entityTypes">
                <label for="entityTypes">Entity types</label>
                <div id="edit-entityTypes" class="form-checkboxes">
                    <div class="form-item form-type-checkbox" ng-repeat="(machineName, entityType) in available.entityTypes">
                        <input
                            type="checkbox"
                            id="edit-entityType-{{machineName}}"
                            name="entityTypes[{{machineName}}]"
                            value="{{machineName}}"
                            class="form-checkbox" />
                        <label class="option" for="edit-entityType-{{machineName}}">
                            {{entityType.humanName}}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-item form-type-checkboxes form-item-fields">
                <label for="entityTypes">Fields</label>
                <div id="edit-fields" class="form-checkboxes">
                    <div class="form-item form-type-checkbox" ng-repeat="(machineName, field) in available.fields">
                        <input
                            type="checkbox"
                            id="edit-fields-{{machineName}}"
                            name="fields[{{machineName}}]"
                            value="{{machineName}}"
                            class="form-checkbox" />
                        <label class="option" for="edit-fields-{{machineName}}">
                            {{field.humanName}}
                        </label>

                        <div class="description">{{field.drupalFieldInfo.description}}</div>
                    </div>
                </div>
            </div>

            <div class="form-actions form-wrapper" id="edit-actions">
                <input type="submit"
                       id="edit-submit"
                       name="op"
                       value="Save"
                       class="form-submit"
                       ng-click="submit()" />
            </div>
        </form>

    </div>
</div>
