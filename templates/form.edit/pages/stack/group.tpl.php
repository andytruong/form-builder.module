<div ng-if="itemInfo.editing">
  <div class="form-item fomr-type-textfield">
    <input type="text"
           size="60"
           maxlength="255"
           class="form-text required"
           ng-model="entity.layoutOptions.pages[pageUuid].groups[itemInfo.uuid].title"
           ng-model-options="{ updateOn: 'blur' }"/>
  </div>

  <div class="form-item form-type-select">
    <label for="edit-language">Group type</label>
    <select class="form-select" ng-model="entity.layoutOptions.pages[pageUuid].groups[itemInfo.uuid].type">
      <option value="fieldset">Fieldset</option>
      <option value="vtabs">Vertical tabs</option>
      <option value="htabs">Horizontal tabs</option>
    </select>
  </div>

  <!-- Config for fieldset -->
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

  <!-- Config for vtabs -->
  <div class="group-options" ng-if="'vtabs' === itemInfo.type">
  </div>

  <!-- Config for htabs -->
  <div class="group-options" ng-if="'htabs' === itemInfo.type">
  </div>
</div>

<?php include 'group.stack.tpl.php'; ?>
