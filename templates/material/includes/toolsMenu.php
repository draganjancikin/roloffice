<?php
require 'add.php';
require 'edit.php';
require 'del.php';
?>
<div class="col-lg-12 px-2" id="topMeni">
  <div class="card mb-2">
    <div class="card-body py-1 px-2">

      <a href="index.php?new" class="btn btn-sm btn-outline-secondary" title="Dodavanje novog materijala!">
        <i class="fas fa-plus"> <i class="fas fa-inbox"></i> </i>
      </a>
      <?php
      if(isset($_GET['view']) || isset($_GET['edit'])):
        $material_id = filter_input(INPUT_GET, 'material_id');
        $material_data = $material->getMaterial($material_id);
        $material_suppliers = $material->getMaterialSuppliers($material_id);
        $clients = $entityManager->getRepository('\Roloffice\Entity\Client')->findBy(array(), array('name' => 'ASC'));
        $suppliers = $entityManager->getRepository('\Roloffice\Entity\Client')->findBy(array('is_supplier' => 1), array('name' => 'ASC'));
        // In view case show edit button.
        if(isset($_GET['view'])):
          ?>
          <a href="?edit&material_id=<?php echo $material_id ?>">
            <button type="button" class="btn btn-sm btn-outline-secondary mx-1" title="Idi na stranicu za izmenu podataka o materijalu!">
              <i class="fas fa-edit"> </i> Izmena
            </button>
          </a>
          <?php
        endif;

        // in edit case show view button
        if(isset($_GET['edit'])):
          ?>
          <a href="?view&material_id=<?php echo $material_id ?>">
            <button type="button" class="btn btn-sm btn-outline-secondary mx-1" title="Idi na stranicu za pregled podataka o materijalu!">
              <i class="fas fa-eye"> </i> Pregled
            </button>
          </a>
          <?php
        endif;
        ?>
        <!-- Button trigger modal za dodavanje dobavljača -->
        <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#addSupplier" title="Dodaj novog dobavljača!">
          <i class="fas fa-plus"> </i> Dobavljač
        </button>

        <!-- Button trigger modal za dodavanje nove osobine proizvoda -->
        <button class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#addProperty" title="Dodaj novu osobinu!">
          <i class="fa fa-plus"> </i> Osobina
        </button>

        <?php
      endif;
      ?>

    </div>
  </div>
  <!-- End Card -->
</div>
<!-- /#topMeni -->
