<li     class="draggable field"
        ng-repeat="itemInfo in pageStack[pageUuid]|orderBy:'weight'"
        ng-if="!itemInfo.isGroup && !itemInfo.parent"
        ui-draggable="true"
        drag-channel="fieldInRoot"
        drag='{ "itemInfo": {{itemInfo}} }'
        drop-channel="*"
        drop-validate="fieldDragValidate($channel, $data)"
        ui-on-Drop="fieldOnDrop($channel, $data, itemInfo.uuid, pageUuid)">

  <div ng-if="!itemInfo.isGroup">
    <div class="field-actions">
      <ul class="action-links">
        <li ng-if="!groupInfo.isGroup"><a href ng-click="fieldConfig(pageUuid, itemInfo.uuid)">Config</a></li>
        <li ng-if="!groupInfo.isGroup"><a href ng-click="fieldRemove(pageUuid, itemInfo.uuid)">Remove</a></li>
      </ul>
    </div>

    <strong ng-if="!groupInfo.isGroup" class="field-human-name">
      {{entity.fields[itemInfo.uuid].humanName}}
    </strong>

    <span ng-if="!groupInfo.isGroup" class="entity-type-name">
      ({{entity.fields[itemInfo.uuid].entityTypeName}})
    </span>
  </div>
</li>
