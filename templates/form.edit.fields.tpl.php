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