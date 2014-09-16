<div id="availableFields" class="form-item form-type-markup">
    <label for="entityTypes">Available Fields</label>
    <div class="item-list">
        <ul>
            <li ng-repeat="(entityTypeName, entityTypeInfo) in available.entityTypes">
                <h3>{{entityTypeInfo.humanName}}</h3>
                <ul class="entity-fields">
                    <li data-name="{{fieldName}}"
                        ng-repeat="(fieldName, field) in entityTypeInfo.fields"
                        ui-draggable="true"
                        drag="name">
                        <strong class="field-human-name">{{field.humanName}}</strong>
                        <span class="entity-type-name">({{field.entityTypeName}})</span>
                        <div class="description" ng-if="field.description">
                            {{field.description}}
                        </div>
                    </li>

                    <li class="empty" ng-show="isAvailableFieldsEmpty(entityTypeName)">
                        There is no available fields.
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>