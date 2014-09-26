<ul>
    <!-- If there's no field added to page -->
    <li class="draggable empty"
        drop-channel="field"
        ui-on-Drop="fieldOnDrop($event, $data, '', pageUuid)"
        ng-if="groupIsEmpty(pageUuid, groupInfo.uuid)">
        No field.
    </li>

    <!-- fields in group -->
    <li class="draggable field"
        ng-repeat="fieldInfo in pageStack[pageUuid]|orderBy:'weight'"
        ng-init="fieldUuid = fieldInfo.uuid"
        ng-if="(!fieldInfo.isGroup) && (groupInfo.uuid === fieldInfo.parent)"
        ui-draggable="true"
        drag-channel="fieldInGroup"
        drop-channel="*"
        ui-on-Drop="groupFieldOnDrop($channel, pageUuid, groupInfo.uuid, $data, fieldUuid)">

        <div class="field-actions">
            <ul class="action-links">
                <li><a href ng-click="fieldConfig(pageUuid, fieldUuid)">Config</a></li>
                <li><a href ng-click="fieldRemove(pageUuid, fieldUuid)">Remove</a></li>
            </ul>
        </div>

        <strong class="field-human-name">
            {{entity.fields[fieldUuid].humanName}}
        </strong>

        <span class="entity-type-name">({{entity.fields[fieldUuid].entityTypeName}})</span>
    </li>

    <!-- fields in group -->
    <!-- Include parent. -->
</ul>
