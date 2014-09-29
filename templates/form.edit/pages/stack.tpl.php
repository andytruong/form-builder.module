<ul>
  <!-- If there's no field added to page -->
  <li class="draggable empty"
      drop-channel="*"
      drop-validate="fieldDragValidate($channel, $data)"
      ui-on-Drop="fieldOnDrop($channel, $data, null, pageUuid)"
      ng-show="isFieldsEmpty(pageUuid)">
    No field.
  </li>

          ng-attr-ui-draggable="{{itemInfo.isGroup ? false : true}}"
          ng-attr-drag-channel="{{itemInfo.isGroup ? '' : 'fieldInRoot'}}"
          drag='{ "itemInfo": {{itemInfo}} }'
          drop-channel="*"
          drop-validate="fieldDragValidate($channel, $data)"
          ui-on-Drop="fieldOnDrop($channel, $data, itemInfo.uuid, pageUuid)">
    <div ng-if="itemInfo.isGroup"><?php include 'stack/group.tpl.php'; ?></div>
    <div ng-if="!itemInfo.isGroup"><?php include 'stack/field.tpl.php'; ?></div>
  <li ng-attr-class="{{itemInfo.isGroup ? 'draggable group field' : 'draggable field'}}"
      ng-show="!item.parent"
  </li>

  <!-- User drags new field to page, ask server for things… -->
  <li class="adding" ng-repeat="field in available.addingFields[pageUuid]">
    Adding <strong>{{field.humanName}}</strong>…
  </li>
</ul>
