<div id="availableFields" class="form-item form-type-markup">
    <label for="entityTypes">Available Fields</label>
    <div class="item-list">
        <ul>
            <li ng-repeat="(entityTypeName, entityTypeInfo) in available.entityTypes">
                <h3>{{entityTypeInfo.humanName}}</h3>
                <ul class="entity-fields">
                    <li data-name="{{fieldName}}"
                        ng-repeat="(fieldName, fieldInfo) in entityTypeInfo.fields"
                        ui-draggable="true"
                        drag="fieldInfo">
                        <strong class="field-human-name">{{fieldInfo.humanName}}</strong>
                        <span class="entity-type-name">({{fieldInfo.entityTypeName}})</span>
                        <div class="description" ng-if="fieldInfo.description">
                            {{fieldInfo.description}}
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