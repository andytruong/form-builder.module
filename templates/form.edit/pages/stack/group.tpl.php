<li     class="draggable group field"
        ng-repeat="itemInfo in pageStack[pageUuid]|orderBy:'weight'"
        ng-if="itemInfo.isGroup && !fieldInfo.parent">

  <div ng-if="itemInfo.isGroup">
    <div class="field-actions">
      <ul class="action-links">
        <li><a href ng-click="fieldConfig(pageUuid, itemInfo.uuid)">Config</a></li>
        <li><a href ng-click="fieldRemove(pageUuid, itemInfo.uuid)">Remove</a></li>
      </ul>
    </div>

    <strong class="group-title">{{itemInfo.title}} ({{itemInfo.type}})</strong>

    <div class="description">{{itemInfo.description}}</div>

    <div ng-if="itemInfo.editing">
      <div class="group-options" ng-if="'fieldset' === itemInfo.type">
        <div class="form-item form-type-checkbox form-item-collapsible">
          <input type="checkbox" id="edit-collapsible-{{itemInfo.uuid}}" class="form-checkbox" />
          <label class="option" for="edit-collapsible-{{itemInfo.uuid}}">Collapsible</label>
        </div>
        <div class="form-item form-type-checkbox form-item-collapsed">
          <input type="checkbox" id="edit-collapsed-{{itemInfo.uuid}}" class="form-checkbox" />
          <label class="option" for="edit-collapsed-{{itemInfo.uuid}}">Collapsed</label>
        </div>
      </div>
    </div>

    <?php include 'group.stack.tpl.php'; ?>
  </div>
</li>
