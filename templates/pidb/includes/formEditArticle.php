<?php 
$pidb_article_id = $_GET['pidb_article_id'];
$pidb_tip_id = $_GET['pidb_tip_id'];
switch ($pidb_tip_id) {
    case '1':
        $style = 'info';
        break;
    case '2':
        $style = 'secondary';
        break;
    case '4':
        $style = 'warning';
        break;
    default:
        $style = 'default';
        break;
}
// get article data
$article_data = $pidb->getArticleInPidb($pidb_article_id);
?>
<div class="card border-<?php echo $style ?> mb-4">
    <div class="card-header bg-<?php echo $style ?> p-2">
        <h6 class="m-0 font-weight-bold text-white">Promena proizvoda</h6>
    </div>
    <div class="card-body p-2">

        <form action="<?php echo $_SERVER['PHP_SELF']. '?editArticleDataInPidb&pidb_id='.$article_data['pidb_id'].'&pidb_article_id=' .$pidb_article_id ?>" class="form-horizontal" role="form" method="post">
            <input type="hidden" name="article_id" value="<?php echo $article_data['article_id'] ?>" />
            
            <div class="form-group row">
                
                <label for="article_id" class="col-sm-3 col-lg-2 col-form-label text-right">Izaberite proizvod:</label>
                <div class="col-sm-9 col-lg-10">
                    <select name="article_id" id="article_id" class="form-control">
                        <option value="<?php echo $article_data['article_id'] ?>"><?php echo $article_data['article_name'] ?></option>
                        <?php
                        foreach ($all_articles as $article) :
                            ?>
                            <option value="<?php echo $article['id'] ?>"><?php echo $article['name'] ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3 offset-sm-3 offset-lg-2">
                    <button type="submit" class="btn btn-sm btn-success" title="Snimi podatake o klijentu!">
                        <i class="fas fa-save" title="Snimi izmenu"> </i> Snimi izmenu
                    </button>
                </div>
            </div>
        
        </form>
    </div>
</div>
