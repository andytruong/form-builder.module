<div class="form-item form-type-textfield form-item-title">
    <label for="edit-title">Title <span class="form-required" title="This field is required.">*</span></label>
    <input type="text"
           id="edit-title"
           name="title"
           size="60"
           maxlength="255"
           class="form-text required"
           ng-model="entity.title"
           ng-change="slugAuto()" />

    <span class="field-suffix" ng-if="slugDoAuto">
        <small id="edit-name-machine-name-suffix" style="display: inline;">
            <strong class="machine-name-label">Slug:</strong>
            <span class="machine-name-value">{{entity.slug}}</span>
            <span class="admin-link">
                <a href ng-click="slugDisableAuto()">Edit</a>
            </span>
        </small>
    </span>
</div>

<div class="form-item fomr-type-textfield form-item-slug" ng-if="!slugDoAuto">
    <label for="edit-slug">Slug <span class="form-required" title="This field is required.">*</span></label>

    <input type="text"
           id="edit-slug"
           name="slug"
           size="60"
           maxlength="255"
           class="form-text required"
           ng-model="entity.slug" />
</div>
