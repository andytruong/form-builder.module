<div id="availableFields" class="form-item form-type-markup">
  <label for="entityTypes">Available Fields</label>

  <div class="form-item form-type-textfield form-item-available-fields-keyword">
    <input type="text"
           size="60"
           class="form-text"
           placeholder="Filter…"
           ng-model="search" />
  </div>

  <div class="item-list">
    <ul>
      <li ng-repeat="(entityTypeName, entityTypeInfo) in available.entityTypes"
          ng-if="entity.entityTypes[entityTypeName]">
        <h3>{{entityTypeInfo.humanName}}</h3>

        <ul class="entity-fields">
          <li data-name="{{fieldName}}"
              ng-repeat="(fieldName, fieldInfo) in entityTypeInfo.fields | toArray | filter:search"
              ng-if="!isFieldAdded(entityTypeName, fieldInfo.name)">

            <div class="drag-icon"
                 ui-draggable="true"
                 drag-channel="newField"
                 drag='{ "itemInfo": {{fieldInfo}} }'></div>

            <strong class="field-human-name">{{fieldInfo.humanName}}</strong>
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