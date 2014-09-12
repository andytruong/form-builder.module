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