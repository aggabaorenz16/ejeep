<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Jeeps</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link rel="stylesheet" href="./jeepcss.css">
</head>
<body>
    <div class="header">
        <div class="title">
            <h4>E-Jeep Payment System</h4>
        </div>
    </div>
    <div class="body">
        <p>Destination</p>
        <div class="selected">
            <select name="from" id="fromSelect" onchange="checkSelections()">
                <option hidden>From</option>
                <option value="Pearl Drive">Pearl Drive</option>
                <option value="Commonwealth">Commonwealth</option>
                <option value="Cubao">Cubao</option>
                <option value="SM Fairview">SM Fairview</option>
            </select>
            <select name="to" id="toSelect" onchange="checkSelections()">
                <option hidden>To</option>
                <option value="SM Fairview / Robinson" data-price="15">Fairview / Robinson</option>
                <option value="Nova Simbahan" data-price="15">Nova Simbahan</option>
                <option value="Commonwealth" data-price="12">Commonwealth</option>
                <option value="Cubao" data-price="20">Cubao</option>
            </select>
            <select name="discount" id="discount" onchange="checkSelections()">
                <option hidden>Discount</option>
                <option value="Regular" data-discount="0">Regular</option>
                <option value="PWD" data-discount="5">PWD</option>
                <option value="Student" data-discount="2">Student</option>
                <option value="Senior Citizen" data-discount="2">Senior Citizen</option>
            </select>
            
        </div>
        <button id="getFareButton" onclick="getSelectedValues()" disabled>Get Fare</button>
    </div>

    <script>
        function checkSelections() {
            var fromSelect = document.getElementById("fromSelect");
            var toSelect = document.getElementById("toSelect");
            var discountSelect = document.getElementById("discount");
            var getFareButton = document.getElementById("getFareButton");

            if (fromSelect.value && toSelect.value) {
                getFareButton.disabled = false;
            } else {
                getFareButton.disabled = true;
            }
        }

        function getSelectedValues() {
            var fromSelect = document.getElementById("fromSelect");
            var toSelect = document.getElementById("toSelect");
            var discountSelect = document.getElementById("discount");

            var fromOption = fromSelect.options[fromSelect.selectedIndex];
            var toOption = toSelect.options[toSelect.selectedIndex];
            var discountOption = discountSelect.options[discountSelect.selectedIndex];

            var fromValue = fromOption.value;
            var toValue = toOption.value;
            var toPrice = parseFloat(toOption.getAttribute('data-price'));

            var discountValue = discountOption ? parseFloat(discountOption.getAttribute('data-discount')) : 0;
            var finalPrice = toPrice - discountValue;

            var selecFrom = "Selected From: " + fromValue;
            var selecTo = "Selected To: " + toValue;
            var totalFare = "Total Price : " + finalPrice.toFixed(2);
            var details = "Details of your Transaction";

            generatePDF(details, selecFrom, selecTo, totalFare, fromValue + " to " + toValue);

            Swal.fire({
                title: 'Success!',
                text: 'Your fare details PDF has been generated.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }

        function generatePDF(details, from, to, fare, barcodeText) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Get the current date and time
            const now = new Date();
            const dateString = now.toLocaleDateString();
            const timeString = now.toLocaleTimeString();

            doc.setFontSize(15);
            doc.text(details, 20, 20);
            doc.text(from, 20, 30);
            doc.text(to, 20, 40);
            doc.text(fare, 20, 50);
            doc.text("Date: " + dateString, 20, 60);
            doc.text("Time: " + timeString, 20, 70);

            // Generate the barcode
            const canvas = document.createElement('canvas');
            JsBarcode(canvas, barcodeText, { format: 'CODE128' });

            // Add the barcode to the PDF
            const barcodeImage = canvas.toDataURL('image/png');
            doc.addImage(barcodeImage, 'PNG', 30, 80, 100, 40); // Adjust the position and size as needed

            doc.save('fare-details.pdf');
        }
    </script>
</body>
</html>
