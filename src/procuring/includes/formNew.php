<!-- New order -->
<div class="card mb-4">
  <div class="card-header p-2">
    <h6 class="m-0 text-dark">Otvaranje nove narudžbenice:</h6>
  </div>
  <div class="card-body p-2">
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?new&addOrder'; ?>" method="post">
      
    <div class="form-group row">
        <label for="selectSupplier" class="col-sm-3 col-lg-2 col-form-label text-right">Dobavljač: </label>
        <div class="col-sm-4">
          <select id="selectSupplier" class="form-control" name="supplier_id" required>
            <?php
            if(isset($_GET['supplier_id'])){
              $supplier_id = htmlspecialchars($_GET["supplier_id"]);
              $supplier_data = $client->getSupplier($supplier_id);
              echo '<option value="'.$supplier_data['id'].'">'.$supplier_data['name'].'</option>';
            }else{
              echo '<option value="">izaberi dobavljača</option>';
            }
            
            $suppliers = $client->getSuppliers();
            foreach ($suppliers as $supplier) {
              echo '<option value="' .$supplier['id']. '">' .$supplier['name']. '</option>';
            }
            ?>
          </select>
        </div>
      </div>
    
    <div class="form-group row">
        <label for="selectProject" class="col-sm-3 col-lg-2 col-form-label text-right">Projekat: </label>
        <div class="col-sm-4">
          <select id="selectProject" class="form-control" name="project_id">
            <?php
            if(isset($_GET['project_id'])){
              $project_id = htmlspecialchars($_GET["project_id"]);
              $project_data = $project->getProject($project_id);
              $client_data = $client->getClient($project_data['client_id']);
              echo '<option value="'.$project_data['id'].'">' .$project_data['pr_id']. ' ' .$client_data['name']. ': ' .$project_data['title'].'</option>';
            }else{
              echo '<option value="">izaberi projekat</option>';
            }
            
            $projects = $project->getProjects();
            foreach ($projects as $project) {
              echo '<option value="' .$project['id']. '">' .$project['pr_id']. ' ' .$project['client_name']. ': ' .$project['title'].'</option>';
            }
            
            ?>
          </select>
        </div>
      </div>

      

      <div class="form-group row">
        <label for="inputTitle" class="col-sm-3 col-lg-2 col-form-label text-right" >Naslov: </label>
        <div class="col-sm-6">
          <input id="inputTitle" class="form-control" type="text" name="title" placeholder="Unesite naslov narudžbenice" />
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-lg-2 col-form-label text-right">Beleška: </label>
        <div class="col-sm-10">
          <textarea class="form-control" rows="3" name="note" placeholder="Unesite belešku uz narudžbenicu"></textarea>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-sm-3 offset-sm-3 offset-lg-2">
          <button type="submit" class="btn btn-sm btn-success" title="Snimi izmene podataka o narudžbenici!">
            <i class="fas fa-save"></i> Snimi
          </button>
        </div>
        </div>

    </form>
  </div>
  <!-- End Card Body -->
</div>
<!-- End Card -->
