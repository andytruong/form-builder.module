<div id="availableEntityTypes" class="form-item form-type-checkboxes form-item-entityTypes">
    <label for="entityTypes">Entity types</label>
    <div id="edit-entityTypes" class="form-checkboxes">
        <div class="form-item form-type-checkbox" ng-repeat="(machineName, entityType) in available.entityTypes">
            <input
                type="checkbox"
                id="edit-entityType-{{machineName}}"
                name="entityTypes[{{machineName}}]"
                value="{{machineName}}"
                class="form-checkbox"
                ng-model="entity.entityTypes[machineName]"
                ng-click="entityTypeToggle(machineName)"
                ng-disabled="available.addingEntityTypeNames[machineName]" />

            <label class="option" for="edit-entityType-{{machineName}}">
                {{entityType.humanName}}
                <span class="adding" ng-if="available.addingEntityTypeNames[machineName]">adding…</span>
            </label>
        </div>
    </div>
</div>
