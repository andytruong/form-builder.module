<ul>
  <!-- If there's no field added to page -->
  <li class="draggable empty"
      drop-channel="*"
      drop-validate="fieldDragValidate($channel, $data)"
      ui-on-Drop="groupFieldOnDrop($channel, $data, pageId, itemInfo.uuid, fieldUuid)"
      ng-if="groupIsEmpty(pageId, itemInfo.uuid)">
    No field.
  </li>

  <!-- fields in group -->
  <li class="draggable field"
      ng-repeat="fieldInfo in pageStack[pageId]|orderBy:'weight'"
      ng-init="fieldUuid = fieldInfo.uuid"
      ng-if="(!fieldInfo.isGroup) && (itemInfo.uuid === fieldInfo.parent)">

    <div class="dropzone before"
         drop-channel="*"
         ui-on-Drop="groupFieldOnDrop($channel, $data, pageId, itemInfo.uuid, fieldUuid, -1)"></div>

    <div ui-draggable="true"
         drag-channel="fieldInGroup"
         drag='{ "pageId": "{{pageId}}", "groupUuid": "{{itemInfo.uuid}}", "itemInfo": {{fieldInfo}} }'>
      <div class="field-actions">
        <ul class="action-links">
          <li><a href ng-click="fieldConfig(pageId, fieldUuid)">Config</a></li>
          <li><a href ng-click="fieldRemove(pageId, fieldUuid)">Remove</a></li>
        </ul>
      </div>

      <strong class="field-human-name">
        {{entity.fields[fieldUuid].humanName}}
      </strong>

      <span class="entity-type-name">({{entity.fields[fieldUuid].entityTypeName}})</span>
    </div>

    <div class="dropzone after"
         drop-channel="*"
         ui-on-Drop="groupFieldOnDrop($channel, $data, pageId, itemInfo.uuid, fieldUuid, 1)"></div>
  </li>

  <!-- fields in group -->
  <!-- Include parent. -->
</ul>
