<div id="availableEntityTypes" class="form-item form-type-checkboxes form-item-entityTypes">
    <div class="form-item form-type-select form-item-entity-types">
        <label for="edit-language">Add an entity type</label>
        <select id="edit-entity-types"
                name="entity-types"
                class="form-select"
                ng-model="entityTypeAdding"
                ng-options="entityType.name as entityType.humanName for (machineName, entityType) in available.entityTypes|toArray">
            <option value="">- Select an entity type -</option>
        </select>
    </div>
</div>
