<div class="card mb-4">
  <div class="card-header p-2">
    <h6 class="m-0 font-weight-bold text-primary">Zadnji upisani materijali</h6>
  </div>
  <div class="card-body p-2">
    <div class="table-responsive">
      <table class="table table-bordered table-hover" id="" width="100%" cellspacing="0">
        <thead class="thead-light">
          <tr>
            <th>naziv</th>
            <th class="text-center">jed. mere</th>
            <th class="text-center">cena <br />(RSD sa PDV-om)</th>
            <th class="text-center">cena <br />(&#8364; sa PDV-om)</th>
            <th>dobavljač</th>
          </tr>
        </thead>
        <tfoot class="thead-light">
          <tr>
            <th>naziv</th>
            <th class="text-center">jed. mere</th>
            <th class="text-center">cena <br />(RSD sa PDV-om)</th>
            <th class="text-center">cena <br />(&#8364; sa PDV-om)</th>
            <th>dobavljač</th>
          </tr>
        </tfoot>
        <tbody>
        <?php
        $materials = $material->getLastMaterials(10);
        foreach ($materials as $material_one):
          ?>
          <tr>
            <td><a href="?view&material_id=<?php echo $material_one['id'] ?>"><?php echo $material_one['name'] ?></a></td>
            <td class="text-center"><?php echo $material_one['unit_name'] ?></td>
            <td class="text-right"><?php echo number_format( ($material_one['price'] * $material->getKurs() * ($material->getTax()/100 + 1) ) , 2, ",", ".") ?></td>
            <td class="text-right"><?php echo number_format( ($material_one['price'] * ($material->getTax()/100 + 1) ) , 1, ",", ".") ?></td>
            <td><?php echo $material_one['client_name'] ?></td>
          </tr>
          <?php
        endforeach;
        ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- End Card Body -->
</div>
