<?php include 'includes/session.php'; ?>
<?php
	$conn = $pdo->open();

	$slug = $_GET['product'];

	try{
		 		
	    $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid FROM products LEFT JOIN category ON category.id=products.category_id WHERE slug = :slug");
	    $stmt->execute(['slug' => $slug]);
	    $product = $stmt->fetch();
		
	}
	catch(PDOException $e){
		echo "There is some problem in connection: " . $e->getMessage();
	}

	//page view
	$now = date('Y-m-d');
	if($product['date_view'] == $now){
		$stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid']]);
	}
	else{
		$stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid'], 'now'=>$now]);
	}

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<script>
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<div class="callout" id="callout" style="display:none">
	        			<button type="button" class="close"><span aria-hidden="true">&times;</span></button>
	        			<span class="message"></span>
	        		</div>
		            <div class="row">
		            	<div class="col-sm-6">
		            		<img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" width="100%" class="zoom" data-magnify-src="images/large-<?php echo $product['photo']; ?>">
		            		<br><br>
		            		<form class="form-inline" id="productForm">
		            			<div class="form-group">
			            			<div class="input-group col-sm-5">
			            				
			            				<span class="input-group-btn">
			            					<button type="button" id="minus" class="btn btn-default btn-flat btn-lg"><i class="fa fa-minus"></i></button>
			            				</span>
							          	<input type="text" name="quantity" id="quantity" class="form-control input-lg" value="1">
							            <span class="input-group-btn">
							                <button type="button" id="add" class="btn btn-default btn-flat btn-lg"><i class="fa fa-plus"></i>
							                </button>
							            </span>
							            <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
							        </div><br>
			            			<button type="submit" class="btn btn-primary btn-lg btn-flat"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
			            			<button class="btn btn-success btn-lg btn-flat" onclick="gotowhatsapp()"><i class="fa fa-whatsapp"></i> Order Now</button>

								</div>
		            		</form>
		            	</div>
		            	<div class="col-sm-6">
		            		<h1  class="page-header">							 
							 <input
							  type="text"
							  name="price"
							  id="price"
							  style="border: none;
								border-color: transparent;
								text-align: center;
								background-color:transparent;
								"
						      
							  value="&#8377; <?php echo number_format($product['price'], 2); ?>"
							  readonly
							/></h1>
		            		<h3 ><b>
							<input
							  type="text"
							  name="pname"
							  id="pname"
							  style="border: none;
								border-color: transparent;
								text-align: center;
								background-color:transparent;
								"
						      
							  value="<?php echo $product['prodname']; ?>"
							  readonly
							/></b></h3>
		            		<p><b>Category:</b> <a href="category.php?category=<?php echo $product['cat_slug']; ?>" >
							<input
							  type="text"
							  name="pcat"
							  id="pcat"
							  style="border: none;
								border-color: transparent;
								text-align: center;
								background-color:transparent;
								"
						      
							  value="<?php echo $product['catname']; ?>"
							  readonly
							/>
						</a></p>
		            		<p><b>Description:</b></p>
		            		<p><?php echo $product['description']; ?>

						</p>
		            	</div>
		            </div>
		            <br>
				    <div class="fb-comments" data-href="http://localhost/ecommerce/product.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  	<?php $pdo->close(); ?>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('#add').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		quantity++;
		$('#quantity').val(quantity);
	});
	$('#minus').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		if(quantity > 1){
			quantity--;
		}
		$('#quantity').val(quantity);
	});

});
</script>
<script>
        function gotowhatsapp() {
            
          
            var name = document.getElementById("pname").value;
            var price = document.getElementById("price").value;
            var category = document.getElementById("pcat").value;
			//var desription = document.getElementById("pdes").value;
            var url = "https://wa.me/918281213343?text=" 
            + "NAME: " + name + "%0a"
            + "PRICE: " + price + "%0a"
            + "CATEGORY NAME: " + category ;
			//+ "DESCRIPTION: " + description  ;
			window.open(url, '_blank').focus();
        }
        </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
</body>
</html>