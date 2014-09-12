<div ng-app="FormBuilderApp">
    <div ng-controller="FormBuilderForm">
        <form>
            <?php include 'form.edit.title.tpl.php'; ?>

            <div id="formBuilderFields">
                <div id="availableResources">
                    <?php include 'form.edit.entity-types.tpl.php'; ?>
                    <?php include 'form.edit.fields-select.tpl.php'; ?>
                </div>

                <div id="formFields" class="form-item form-type-markup">
                    <?php include 'form.edit.fields.tpl.php'; ?>
                </div>
            </div>

            <div class="break"></div>

            <div class="form-item form-type-checkbox form-item-status">
                <input type="checkbox"
                       id="edit-status"
                       name="status"
                       class="form-checkbox"
                       ng-model="entity.status" />
                <label class="option" for="edit-status">Published</label>
            </div>

            <div id="edit-language" class="form-radios">
                <label for="edit-title">Language</label>
                <div class="form-item form-type-radio form-item-language" ng-repeat="item in available.languages| orderBy: weight">
                    <input type="radio"
                           name="language"
                           id="edit-language-{{item.language}}"
                           value="{{item.language}}"
                           checked="checked"
                           ng-model="entity.language"
                           class="form-radio">
                    <label class="option" for="edit-language-{{item.language}}">{{item.name}}</label>
                </div>
            </div>

            <div class="form-actions form-wrapper" id="edit-actions">
                <button id="edit-submit" class="form-submit" ng-click="formSubmit()">
                    <span ng-if="!formSaving">Save</span>
                    <span ng-if="formSaving">Saving…</span>
                </button>
            </div>
        </form>

    </div>
</div>
