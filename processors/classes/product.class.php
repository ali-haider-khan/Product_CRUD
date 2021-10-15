<?php
class product
{
    private $db = null;
    function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        try {
            $this->db = new PDO("mysql:host=$servername;dbname=product", $username, $password);
            // set the PDO error mode to exception
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            die;
        }
    }
    function get_products()
    {
        try {
            $sql = $this->db->prepare('SELECT * FROM product');
            $sql->execute([]);
            if($sql->rowCount() < 1)
            {
                return ['status'=>'No product found',];
            }
            $data = $sql->fetchAll(PDO::FETCH_OBJ);
            return ['status'=>'ok','data'=>$data];
        }
        catch (Exception $e)
        {
            return ['status'=>'tech_error'];
        }
    }
    function get_product($id)
    {
        $id = (int)$id;
        if($id < 1)
        {
            return ['status'=>'Product ID is missing'];
        }
        try {
            $sql = $this->db->prepare('SELECT * FROM product WHERE id = ? Limit 1');
            $sql->execute([$id]);
            if($sql->rowCount() < 1)
            {
                return ['status'=>'Product not found in the system'];
            }
            $data = $sql->fetch(PDO::FETCH_OBJ);
            return ['status'=>'ok','data'=>$data];
        }
        catch (Exception $e)
        {
            return ['status'=>'tech_error'];
        }
    }
    function set_product($data)
    {
        $id    = (int)trim($data['id']);
        $price = (int)trim($data['price']);
        $title = trim($data['title']);
        $desc = trim($data['desc']);

        if(empty($title))
        {
            return ['status'=>'Product title is required'];
        }
        if(strlen($title) > 100)
        {
            return ['status'=>'Product title cannot be greater than 100 characters'];
        }
        if(empty($desc))
        {
            return ['status'=>'Product description is required'];
        }
        if(strlen($title) > 255)
        {
            return ['status'=>'Product descriptiom cannot be greater than 255 characters'];
        }
        if($price < 1)
        {
            return ['status'=>'Product price is required'];
        }
        try
        {
            if($id > 0)//update
            {
                $sql = $this->db->prepare('UPDATE product SET title = ?,description = ?, price = ?');
                $e   = $sql->execute([$title,$desc,$price]);
                if($e)
                {
                    return ['status'=>'ok','message'=>'Product successfully updated'];
                }
                return ['status'=>'technical error occured'];
            }
            else
            {
                $sql = $this->db->prepare('INSERT INTO product(title,description,price,entry_time,status) VALUE(?,?,?,?,?)');
                $e = $sql->execute([$title,$desc,$price,time(),'1']);
                if($e)
                {
                    return ['status'=>'ok','message'=>'product successfully added'];
                }
                return ['status'=>'technical error occured'];
            }

            $data = $sql->fetchAll(PDO::FETCH_OBJ);
            return ['status'=>'ok','data'=>$data];
        }
        catch (Exception $e)
        {
            return ['status'=>'tech_error'];
        }
    }
    function delete_product($id)
    {
        $id    = (int)trim($id);

        if($id < 1)
        {
            return ['status'=>'Product ID not found'];
        }
        try
        {
            $sql = $this->db->prepare('SELECT  * FROM product WHERE id = ? LIMIT 1');
            $sql->execute([$id]);
            if($sql->rowCount() < 1)
            {
                return ['status'=>'Product not found in the system'];
            }
            $sql = $this->db->prepare('DELETE FROM product WHERE id = ? LIMIT 1');
            $e   = $sql->execute([$id]);
            if($e)
            {
                return ['status'=>'ok'];
            }
            return ['status'=>'technical error occured while deleting product data'];
        }
        catch (Exception $e)
        {
            return ['status'=>'tech_error'];
        }
    }
}
$product_obj = new product();

?>