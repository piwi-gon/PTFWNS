<?php
/**
 *
 * chooseRepository.inc.php
 *
 * author : piwi
 *
 * created: 27.10.2015
 * changed: 27.10.2015
 *
 * purpose:
 *
 */


/**
 * first: include the basic-start
 */
require_once(__DIR__."/../lib/baseStart.php");

$repo = $base->getUpdateChecker()->querySystemRepository();
?>
<script>
function storeSelectedRepoIdent(sessId) {
    var repositoryIdent = $('#selectedRepositoryIdentSelectId option:selected').val();
    $.ajax({
        url: 'modules/storeModuleRepository.inc.php?' + sessId + '&selectedRepositoryIdent=' + repositoryIdent,
        type: 'POST',
        success: function(data) {
            $('#installModuleId').prop('disabled', false).removeClass('ui-state-disabled');
            $('#dialog').dialog('close');
            $('#dialog').hide();
        }
    });
}
</script>
<div class="table">
    <div class="trow">
        <div class="tcell">
            <span style="font-weight:bold;font-size:14pt;">Doubleclick to choose</span><br>
            <select name="selectedRepositoryIdentSelect" id="selectedRepositoryIdentSelectId" size="10" style="width:300px;height:250px;" onDblClick="storeSelectedRepoIdent('<?php echo SID ;?>');">
<?php
for($count = 0; $count < count($repo['modules']['reponame']); $count++) {
?>
                <option value="<?php echo $repo['modules']['reponame'][$count]; ?>"><?php echo $repo['modules']['reponame'][$count]; ?></option>
<?php
}
?>
            </select>
        </div>
    </div>
</div>