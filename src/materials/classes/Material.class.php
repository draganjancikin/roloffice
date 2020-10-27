<?php
/**
 * Description of Material class
 *
 * @author Dragan Jancikin
 */

class Material {
    
    // metoda koja se automatski izvršava pri generisanju objekta Client
    public function __construct() {
        // treba konektovati na bazu preko klase koja vrši konekciju
        $db = new DB();
        $this->connection = $db->connectDB();
    }
    

    //metoda koja vraća artikle u zavisnosti od datog pojma u pretrazi
    public function search($name){
        
        $material = array();
        $materials = array();
        
        // izlistavanje iz baze svih artikala sa nazivom koji je sličan $name
        $result = $this->connection->query("SELECT material.id, material.name, material.note, unit.unit_name, material_suppliers.price, material_suppliers.client_id "
                                         . "FROM material "
                                         . "JOIN (unit, material_suppliers) "
                                         . "ON (material.unit_id = unit.id AND material.id = material_suppliers.material_id) "
                                         . "WHERE (material.name LIKE '%$name%') "
                                         . "ORDER BY material.name ") or die(mysqli_error($this->connection));
        
        /*
        $result = $this->connection->query("SELECT material.id, material.name, unit.unit_name, material.price "
                                         . "FROM material "
                                         . "JOIN (unit) "
                                         . "ON material.unit_id = unit.id "
                                         . "WHERE (material.name LIKE '%$name%') "
                                         . "ORDER BY material.name ") or die(mysql_error());
         */
        while($row = mysqli_fetch_array($result)):
            $id = $row['id'];
            $name = $row['name'];
            $note = $row['note'];
            $unit_name = $row['unit_name'];
            $price = $row['price'];
            $client_id = $row['client_id'];
            
            $result_client = $this->connection->query("SELECT client.name "
                                         . "FROM client "
                                         . "WHERE id = $client_id ") or die(mysqli_error($this->connection));
            $row_client = mysqli_fetch_array($result_client);
            $client_name = $row_client['name'];
            
            $material = array(
                'id' => $id,
                'name' => $name,
                'note' => $note,
                'unit_name' => $unit_name,
                'price' => $price,
                'client_name' => $client_name
            );
            
            array_push($materials, $material);
            
        endwhile;
        
        return $materials;
        
    }
    
    
    // metoda koja daje cenu materijala
    public function getPrice ($material_id){
        
        // sada treba isčitati cenu artikla
        $result = $this->connection->query("SELECT price FROM material WHERE id = $material_id " ) or die(mysqli_error($this->connection));
        $row = mysqli_fetch_array($result);
         
            $price = $row['price'];
            
        return $price;
    }
    
    
    // metoda koja daje vrednost poreza
    public function getTax(){
        
        $results = $this->connection->query("SELECT * FROM preferences WHERE id = '1' ") or die(mysqli_error($this->connection));
            $row = mysqli_fetch_array($results);
            $tax = $row['tax'];
        return $tax;
    }
    
    
    //metoda koja vraća podatke o materialu 
    public function getMaterial($material_id){
        
        $result = $this->connection->query("SELECT material.id, material.name, material.unit_id, material.weight, material.price, material.note, unit.unit_name "
                                         . "FROM material "
                                         . "JOIN (unit) "
                                         . "ON (material.unit_id = unit.id) "
                                         . "WHERE material.id = $material_id ") or die(mysqli_error($this->connection));
        $row = mysqli_fetch_array($result);
            $id = $row['id'];
            $name = $row['name'];
            $unit_id = $row['unit_id'];
            $unit_name = $row['unit_name'];
            $weight = $row['weight'];
            $price = $row['price'];
            $note = $row['note'];
            
            $material = array(
                'id' => $id,
                'name' => $name,
                'unit_id' => $unit_id,
                'unit_name' => $unit_name,
                'weight' => $weight,
                'price' => $price,
                'note' => $note
            );
            
        return $material;
        
    }
    
    
    //metoda koja vraća sve materijale
    public function getMaterials(){
        
        $material = array();
        $materials = array();
        
        $result = $this->connection->query("SELECT id, name, note FROM material ORDER by name") or die(mysqli_error($this->connection));
        while($row = mysqli_fetch_array($result)):
            $id = $row['id'];
            $name = $row['name'];
            $note = $row['note'];
            
            $material = array(
                'id' => $id,
                'name' => $name,
                'note' => $note
            );
            
        array_push($materials, $material);
            
        endwhile;
        
        return $materials;
        
    }
    
    
    // metoda koja daje sve jedinicec mere
    public function getUnits (){
        
        $unit = array();
        $units = array();

        // sada treba isčitati sve klijente iz tabele client
        $result = $this->connection->query("SELECT id, unit_name FROM unit ORDER BY unit_name" ) or die(mysqli_error($this->connection));
        while($row = mysqli_fetch_array($result)){
         
            $id = $row['id'];
            $unit_name = $row['unit_name'];

            $unit = array(
                'id' => $id,
                'name' => $unit_name
            );
 
            array_push($units, $unit);
        }
        
        return $units;
    }
    
    
    //metoda koja daje zadnjih $number materijala upisanih u bazu
    public function getLastMaterials($limit){
        
        $material = array();
        $materials = array();
        
        // izlistavanje zadnjih $limit materijala
        $result = $this->connection->query("SELECT material.id, material.name, unit.unit_name, material_suppliers.price, material_suppliers.client_id "
                                         . "FROM material "
                                         . "JOIN (unit, material_suppliers)"
                                         . "ON (material.unit_id = unit.id AND material.id = material_suppliers.material_id)"
                                         . "ORDER BY material.id DESC LIMIT $limit") or die(mysqli_error($this->connection));
        /*
        $result = $this->connection->query("SELECT material.id, material.name, unit.unit_name, material.price "
                                         . "FROM material "
                                         . "JOIN (unit)"
                                         . "ON (material.unit_id = unit.id)"
                                         . "ORDER BY material.id DESC LIMIT $limit") or die(mysql_error());
         */
        
        while($row = mysqli_fetch_array($result)):
            $id = $row['id'];
            $name = $row['name'];
            $unit_name = $row['unit_name'];
            $price = $row['price'];
            $client_id = $row['client_id'];
            
            $result_client = $this->connection->query("SELECT client.name "
                                         . "FROM client "
                                         . "WHERE id = $client_id ") or die(mysqli_error($this->connection));
            $row_client = mysqli_fetch_array($result_client);
            $client_name = $row_client['name'];
            
            $material = array(
                'id' => $id,
                'name' => $name,
                'unit_name' => $unit_name,
                'price' => $price,
                'client_name' => $client_name
            );
            
            array_push($materials, $material);
            
        endwhile;
        
        return $materials;
    }
    
    
    // metoda koja daje sve materijale od kojih je sastavljen artikal
    public function getArticleMaterials ($id){
        
        $material = array();
        $materials = array();
        
        $result = $this->connection->query("SELECT material.id, material.name, article_material.function "
                                         . "FROM material "
                                         . "JOIN ( article_material ) "
                                         . "ON ( material.id = article_material.material_id ) "
                                         . "WHERE article_material.article_id = $id " ) or die(mysqli_error($this->connection));
        
        while($row = mysqli_fetch_array($result)){
        
            $id = $row['id'];
            $name = $row['name'];
            $function = $row['function'];
            
            $material = array(
                'id' => $id,
                'name' => $name,
                'function' => $function
            );
            
            array_push($materials, $material);
        
        }
                
        return $materials;
    }
    
    
    //metoda koja daje dobavljače materijala
    public function getSuppliers($material_id){
        
        $supplier = array();
        $suppliers = array();
        
        // izlistavanje zadnjih $limit materijala
        
        
        $result = $this->connection->query("SELECT client.id, client.name, material_suppliers.code, material_suppliers.price "
                                         . "FROM material_suppliers "
                                         . "JOIN (client) "
                                         . "ON (material_suppliers.material_id = $material_id AND material_suppliers.client_id = client.id) "
                                         . "ORDER BY client.name") or die(mysqli_error($this->connection));
        
        while($row = mysqli_fetch_array($result)):
            $id = $row['id'];
            $name = $row['name'];
            $code = $row['code'];
            $price = $row['price'];
            
            $supplier = array(
                'id' => $id,
                'name' => $name, 
                'code' => $code,
                'price' => $price
            );
            
            array_push($suppliers, $supplier);
            
        endwhile;
        
        return $suppliers;
    }
    

    // metoda koja briše dobavljač materijala
    public function delMaterialSupplier($material_id, $client_id){
        
        $this->connection->query("DELETE FROM material_suppliers WHERE material_id='$material_id' AND client_id='$client_id' ") or die(mysqli_error($this->connection));
        
    }
    
    
    // metoda koja vraća property-je materijala, ako postoje, na osnovu material_id-a
    public function getPropertyById($material_id){
        
        $property = array();
        $propertys = array();
        
        // sada treba isčitati property-je  materijala na osnovu material_id-a
        $result = $this->connection->query("SELECT property.id, property.name "
                                         . "FROM material_property "
                                         . "JOIN (property) "
                                         . "ON (material_property.property_id = property.id) "
                                         . "WHERE material_property.material_id = $material_id "  ) or die(mysqli_error($this->connection));
        
        while($row = mysqli_fetch_array($result)){
            
            $id = $row['id'];
            $name = $row['name'];
            
            $property = array(
                'id' => $id,
                'name' => $name
            );
            
            array_push($propertys, $property);
        }
        
        return $propertys;
    }
    
    
    // metoda koja vraća property-je 
    public function getPropertys(){
        
        $property = array();
        $propertys = array();
        
        // sada treba isčitati property-je  artikla
        $result = $this->connection->query("SELECT id, name "
                                         . "FROM property "  ) or die(mysqli_error($this->connection));
        while($row = mysqli_fetch_array($result)){
            $id = $row['id'];
            $name = $row['name'];
            
            $property = array(
                'id' => $id,
                'name' => $name
            );
            
            array_push($propertys, $property);
        }
        
        return $propertys;
    }
    
    
    // metoda koja briše osobinu materijala
    public function delMaterialProperty($material_id, $property_id) {
        
        $this->connection->query("DELETE FROM material_property WHERE ( material_id='$material_id' AND property_id='$property_id') ") or die(mysqli_error($this->connection));
        
    }
    
}