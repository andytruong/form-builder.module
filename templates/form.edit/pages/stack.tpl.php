<ul>
    <!-- If there's no field added to page -->
    <li class="draggable empty"
        drop-channel="field"
        ui-on-Drop="fieldOnDrop($event, $data, '', pageUuid)"
        ng-show="isFieldsEmpty(pageUuid)">
        No field.
    </li>

    <?php include 'stack/field.tpl.php'; ?>
    <?php include 'stack/group.tpl.php'; ?>

    <!-- User drags new field to page, ask server for things… -->
    <li class="adding" ng-repeat="field in available.addingFields[pageUuid]">
        Adding <strong>{{field.humanName}}</strong>…
    </li>
</ul>
