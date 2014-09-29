<div class="field-actions">
  <ul class="action-links">
    <li><a href ng-click="fieldConfig(pageUuid, itemInfo.uuid)">Config</a></li>
    <li><a href ng-click="fieldRemove(pageUuid, itemInfo.uuid)">Remove</a></li>
  </ul>
</div>

<strong class="field-human-name">{{entity.fields[itemInfo.uuid].humanName}}</strong>
<span class="entity-type-name">({{entity.fields[itemInfo.uuid].entityTypeName}})</span>
