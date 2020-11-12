<!-- Edit Material Data -->
<div class="card mb-4">
    <div class="card-header p-2">
        <h6 class="m-0 text-dark">Izmena materiala: <strong><?php echo $material_data['name']; ?></strong></h6>
    </div>
    <div class="card-body p-2">
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?editMaterial&material_id=' .$material_id; ?>" method="post">

            <div class="form-group row">
                <label for="inputName" class="col-sm-3 col-lg-2 col-form-label text-right">Naziv:</label>
                <div class="col-sm-9">
                    <input class="form-control" id="inputName" type="text" name="name" value="<?php echo $material_data['name']; ?>" maxlength="96">
                </div>
            </div>

            <div class="form-group row">
                <label for="selectUnit" class="col-sm-3 col-lg-2 col-form-label text-right">Jedinica mere:</label>
                <div class="col-sm-3">
                <select id="selectUnit" name="unit_id" class="form-control">
                    <option value="<?php echo $material_data['unit_id'];  ?>"><?php echo $material_data['unit_name'];  ?></option>
                    <?php
                        $units = $material->getUnits();
                        foreach ($units as $unit) {
                            echo '<option value="' .$unit['id']. '">' .$unit['name']. '</option>';
                        }
                        ?>
                </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputWeight" class="col-sm-3 col-lg-2 col-form-label text-right">Težina:</label>
                <div class="col-sm-2">
                    <input class="form-control" id="inputWeight" type="text" name="weight" value="<?php echo $material_data['weight']; ?>" >
                </div>
                <div class="col-sm-2">g</div>
            </div>

            <div class="form-group row">
                <label for="inputPrice" class="col-sm-3 col-lg-2 col-form-label text-right">Cena:</label>
                <div class="col-sm-2">
                    <input class="form-control" id="inputPrice" type="text" name="price" value="<?php echo $material_data['price']; ?>" >
                </div>
                <div class="col-sm-2">EUR bez PDV-a</div>
            </div>   

            <div class="form-group row">
                <label for="inputNote" class="col-sm-3 col-lg-2 col-form-label text-right">Beleška: </label>
                <div class="col-sm-9">
                    <textarea class="form-control" id="inputNote" rows="3" name="note" placeholder="Beleška uz materijal ..."><?php echo $material_data['note']; ?></textarea>	
                </div>
            </div> 

            <div class="form-group row">
                <div class="col-sm-3 offset-sm-3 offset-lg-2"><button type="submit" class="btn btn-sm btn-success" title="Snimi izmene podataka o klijentu!"><i class="fas fa-save"></i> Snimi</button></div>
            </div> 

        </form>

    </div>
    <!-- End card Body -->

</div>
<!-- End Card -->

<div class="card mb-4">

    <div class="card-header p-2">
        <h6 class="m-0 text-dark">Pregled dobavljača</h6>
    </div>

    <div class="card-body p-2">
        <?php
        foreach ($material_suppliers as $material_supplier):
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] . '?editMaterialSupplier&material_id=' .$material_id; ?>" method="post">
                <input class="form-control" type="hidden" name="material_id" value="<?php echo $material_id; ?>" />
                <input class="form-control" type="hidden" name="client_id_temp" value="<?php echo $material_supplier['id']; ?>" />

                <div class="form-group row">

                    <div class="col-sm-5">
                        <select class="form-control" name="client_id" required>
                        <option value="<?php echo $material_supplier['id']; ?>"><?php echo $material_supplier['name']; ?></option>
                        <?php
                        foreach ($suppliers as $supplier) {
                            echo '<option value="' .$supplier['id']. '">' .$supplier['name']. '</option>';
                        }
                        ?>
                        </select>
                    </div>

                <div class="col-sm-2">
                    <input class="form-control" type="text" name="code" value="<?php echo $material_supplier['code']; ?>">
                </div> 

                <div class="col-sm-3">
                    <input class="form-control" type="text" name="price" value="<?php echo $material_supplier['price']; ?>">
                </div>

                <div class="col-sm-2">
                    <button type="submit" class="btn btn-mini btn-success"><i class="fas fa-save"> </i> </button>
                    <a href="<?php echo $_SERVER['PHP_SELF']. '?edit&material_id=' .$material_id. '&client_id_temp=' .$material_supplier['id']. '&delMaterialSupplier'; ?>" class="btn btn-mini btn-danger"><i class="fas fa-trash-alt"> </i> </a>
                </div>

                </div>
            </form>
            <?php
        endforeach;
        ?>
    </div>
    <!-- End Card Body -->

    <div class="card-header p-2">
        <h6 class="m-0 text-dark">Pregled osobina materijala</h6>
    </div>

    <div class="card-body p-2">
        <?php
        $propertys = $material->getPropertysByMaterialId($material_id);
        foreach ($propertys as $property):
            ?>
            <form method="post">
                <div class="form-group row">

                    <div class="col-sm-4">
                        <select class="form-control" name="material_id">
                            <option value="<?php echo $property['id']; ?>"><?php echo $property['name']; ?></option>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <a href="<?php echo $_SERVER['PHP_SELF'] . '?delProperty&material_id=' .$material_id. '&property_id=' .$property['id']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"> </i> </a>
                    </div>

                </div>

            </form>
            <?php
        endforeach;
        ?>
    </div>
    <!-- End Card Body -->

</div>
