<label>Form fields</label>

<div class="item-list">
    <ul>
        <li ng-repeat="(pageUuid, pageInfo) in entity.layoutOptions">
            <h2>{{pageInfo.title}}</h2>
            <div class="description">{{pageInfo.description}}</div>
            <ul>
                <li class="draggable empty"
                    ui-on-Drop="fieldOnDrop($event, $data, '', pageUuid)"
                    ng-show="isFieldsEmpty(pageUuid)">
                    No field.
                </li>
                <li     class="draggable field"
                        ng-repeat="fieldInfo in pageFields[pageUuid]|orderBy:'weight'"
                        ui-on-Drop="fieldOnDrop($event, $data, fieldInfo.uuid, pageUuid)"
                        ui-draggable="true"
                        drag="fieldInfo.uuid">
                    <strong class="field-human-name">{{entity.fields[fieldInfo.uuid].humanName}}</strong>
                    <span class="entity-type-name">({{entity.fields[fieldInfo.uuid].entityTypeName}})</span>
                    <div class="field-actions">
                        <a href ng-click="fieldConfig(field.uuid)">Config</a>
                        <a href ng-click="fieldRemove(field.uuid)">Remove</a>
                        <a href>weight: {{fieldInfo.weight}}</a>
                    </div>
                </li>

                <li class="adding" ng-repeat="field in available.addingFields[pageUuid]">
                    Adding <strong>{{field.humanName}}</strong>…
                </li>
            </ul>
        </li>
    </ul>
</div>


<div class="form-item form-type-textfield form-item-new-page-title">
    <input type="text"
           id="edit-new-page-title"
           name="title"
           size="60"
           maxlength="255"
           class="form-text"
           placeholder="Page name…"
           ng-model="newPageTitle" />
    <button ng-click="newPageClick()">
        Add<span ng-if="newPageAdding">ing…</span>
    </button>
</div>
