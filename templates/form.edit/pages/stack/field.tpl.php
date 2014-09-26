<li     class="draggable field"
        ng-repeat="fieldInfo in pageStack[pageUuid]|orderBy:'weight'"
        ng-if="!fieldInfo.isGroup && !fieldInfo.parent"
        drop-channel="field"
        ui-on-Drop="fieldOnDrop($data, fieldInfo.uuid, pageUuid)"
        ui-draggable="true"
        drag-channel="field"
        drag="fieldInfo.uuid">

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
