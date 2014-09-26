<li     class="draggable field"
        ng-repeat="fieldInfo in pageStack[pageUuid]|orderBy:'weight'"
        ng-if="!fieldInfo.isGroup && !fieldInfo.parent"
        ui-draggable="true"
        drag-channel="fieldInRoot"
        drag='{ "fieldInfo": {{fieldInfo}} }'
        drop-channel="*"
        drop-validate="fieldDragValidate($channel, $data)"
        ui-on-Drop="fieldOnDrop($channel, $data, fieldInfo.uuid, pageUuid)">

    <div class="field-actions">
        <ul class="action-links">
            <li><a href ng-click="fieldConfig(pageUuid, fieldInfo.uuid)">Config</a></li>
            <li><a href ng-click="fieldRemove(pageUuid, fieldInfo.uuid)">Remove</a></li>
        </ul>
    </div>

    <strong class="field-human-name">
        {{entity.fields[fieldInfo.uuid].humanName}}
    </strong>

    <span class="entity-type-name">({{entity.fields[fieldInfo.uuid].entityTypeName}})</span>
</li>
