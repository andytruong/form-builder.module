<ul>
  <!-- If there's no field added to page -->
  <li class="draggable empty"
      drop-channel="*"
      drop-validate="fieldDragValidate($channel, $data)"
      ui-on-Drop="fieldOnDrop($channel, $data, null, pageId)"
      ng-show="isFieldsEmpty(pageId)">
    No field.
  </li>

  <li ng-attr-class="{{itemInfo.isGroup ? 'draggable group field' : 'draggable field'}}"
      ng-show="!itemInfo.parent"
      ng-repeat="itemInfo in pageStack[pageId]|orderBy:'weight'">

    <div class="dropzone before"
         drop-channel="*"
         drop-validate="fieldDragValidate($channel, $data)"
         ui-on-Drop="fieldOnDrop($channel, $data, itemInfo.uuid, pageId, -1)"></div>

    <div>
      <div class="field-actions">
        <ul class="action-links">
          <li><a href ng-click="stackItemConfig(pageId, itemInfo.uuid, itemInfo.isGroup)">Config</a></li>
          <li><a href ng-click="stackItemRemove(pageId, itemInfo.uuid, itemInfo.isGroup)">Remove</a></li>
        </ul>
      </div>

      <div ng-attr-drag-channel="{{itemInfo.isGroup ? 'groupInRoot' : 'fieldInRoot'}}" ui-draggable="true" drag='{ "itemInfo": {{itemInfo}} }'>
        <div ng-if="itemInfo.isGroup">
          <strong class="group-title">{{itemInfo.title}} ({{itemInfo.type}})</strong>
          <div class="description">{{itemInfo.description}}</div>
        </div>

        <div ng-if="!itemInfo.isGroup">
          <strong class="field-human-name">{{entity.fields[itemInfo.uuid].humanName}}</strong>
          <span class="entity-type-name">({{entity.fields[itemInfo.uuid].entityTypeName}})</span>
        </div>
      </div>

      <div ng-if="itemInfo.isGroup"><?php include 'stack/group.tpl.php'; ?></div>
    </div>

    <div class="dropzone after"
         drop-channel="*"
         drop-validate="fieldDragValidate($channel, $data)"
         ui-on-Drop="fieldOnDrop($channel, $data, itemInfo.uuid, pageId, 1)"></div>
  </li>

  <!-- User drags new field to page, ask server for things… -->
  <li class="adding" ng-repeat="field in available.addingFields[pageId]">
    Adding <strong>{{field.humanName}}</strong>…
  </li>
</ul>
