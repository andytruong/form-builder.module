<!-- If there's no field added to page -->
<li class="draggable empty"
    ui-on-Drop="fieldOnDrop($event, $data, '', pageUuid)"
    ng-show="isFieldsEmpty(pageUuid)">
    No field.
</li>

<!-- Field details -->
<li         class="draggable field"
            ng-repeat="fieldInfo in pageFields[pageUuid]|orderBy:'weight'"
            ui-on-Drop="fieldOnDrop($event, $data, fieldInfo.uuid, pageUuid)"
            ui-draggable="true"
            drag="fieldInfo.uuid">
    <strong class="field-human-name">
        {{entity.fields[fieldInfo.uuid].humanName}}
    </strong>
    <span class="entity-type-name">({{entity.fields[fieldInfo.uuid].entityTypeName}})</span>
    <div class="field-actions">
        <a href ng-click="fieldConfig(pageUuid, fieldInfo.uuid)">Config</a>
        <a href ng-click="fieldRemove(pageUuid, fieldInfo.uuid)">Remove</a>
    </div>
</li>

<!-- User drags new field to page, ask server for things… -->
<li class="adding" ng-repeat="field in available.addingFields[pageUuid]">
    Adding <strong>{{field.humanName}}</strong>…
</li>
