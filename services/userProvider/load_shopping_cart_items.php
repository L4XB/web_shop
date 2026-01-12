<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Make sure the user ID is stored in the session
if (!isset($_SESSION['userId'])) {
    die("User ID not found");
}

$userId = $_SESSION['userId'];

// Server connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webShopFSI";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to load products and merge duplicate entries
$sql = "SELECT p.productName, p.description, p.price, s.productID, p.pathName, SUM(s.amount) as amount 
        FROM shoppingCart s 
        JOIN products p ON s.productID = p.productID
        WHERE s.userID = ? 
        GROUP BY s.productID";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Here you can output the HTML code for each product
        echo '<div class="row">';
        echo '<div class="col-2"></div>';
        echo '<div class="col-1" style="justify-content: center; align-items: center; display: flex; border-bottom: solid; border-width:thin; border-color: lightgrey;">';
        echo '<button class="delete" data-productid="' . $row['productID'] . '" style="display: flex; justify-content: center; align-items: center;"><img src="../database/images/trash.png" style="height: 30px;"></button>';
        echo '</div>';
        echo '<div class="col-1" style="justify-content: right; align-items: center; display: flex; border-bottom: solid; border-width:thin; border-color: lightgrey;">';
        echo '<img style="height:45px;" src="../assets/images/produkts/' . $row['pathName'] . '.png">';
        echo '</div>';
        echo '<div class="col-2" style="border-bottom: solid; border-width:thin; border-color: lightgrey;">';
        echo '<p class="light-text">' . $row['productName'] . '</p>';
        echo '<p class="text">' . $row['description'] . '</p>';
        echo '</div>';
        echo '<div class="col-2" style="justify-content: center; align-items: center; display: flex; border-bottom: solid; border-width:thin; border-color: lightgrey;">';
        echo '<div id="counter" data-productid="' . $row['productID'] . '">';
        echo '<div class="minus"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2 8a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11A.5.5 0 0 1 2 8"/>
                    </svg>
                </div>';
        echo '<div class="number">' . $row['amount'] . '</div>';
        echo '<div class="plus"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                </div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="col-1" style="justify-content: right; align-items: center; display: flex; border-bottom: solid; border-width:thin; border-color: lightgrey;">';
        echo '<p>' . $row['price'] . ' €</p>';
        echo '</div>';
        echo '<div class="col-2"></div>';
        echo '</div>';
    }

    echo '<div class="row container-fluid justify-content-end" style="margin-top:40px;padding-right:190px;">
    <div class="col-3">
        <button type="button" class="btn btn-warning" onclick="window.location.href = \'checkout.php\';">Proceed
            to checkout</button>
    </div>
</div>';

} else {
    echo '<p style="text-align:center;">No products in the shopping cart.</p>
    <p style="text-align:center;"><small class="d-block text-right mt-3">
      <a href="products.php" class="btn btn-outline-dark">Add items</a>
    </small></div></p>';
}

$stmt->close();
$conn->close();

?>

<script>
    $(document).ready(function () {
        $(".minus").click(function () {
            var counter = $(this).parent();
            var productId = counter.data('productid');
            var numberDiv = counter.find(".number");
            var number = parseInt(numberDiv.text());
            if (number > 0) {
                number--;
                numberDiv.text(number);
                updateAmount(productId, number);
            }
        });

        $(".plus").click(function () {
            var counter = $(this).parent();
            var productId = counter.data('productid');
            var numberDiv = counter.find(".number");
            var number = parseInt(numberDiv.text());
            number++;
            numberDiv.text(number);
            updateAmount(productId, number);
        });

        function updateAmount(productId, amount) {
            var url = amount > 0 ? "../services/userProvider/update_amount.php" : "../services/userProvider/delete_product.php";
            $.ajax({
                url: url,
                type: "post",
                data: {
                    userId: <?php echo $_SESSION['userId']; ?>,
                    productId: productId,
                    amount: amount
                },
                success: function (response) {

                    if (amount == 0) {
                        location.reload(); // Refresh the page to show the changes
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        }
    });
</script>
