<!DOCTYPE html>
<html>
<head>
    <title>Signature Pad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #signature-pad {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>pad</h1>
    <canvas id="signature-pad" width="400" height="200" style="border:1px solid #000;"></canvas>
    <br>
    <button id="save">sauvegarder</button>
    <button id="clear">Clear</button>

    <script>
        var canvas = document.getElementById('signature-pad');
        var ctx = canvas.getContext('2d');
        var isDrawing = false;

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            draw(e);
        }

        function draw(e) {
            if (!isDrawing) return;

            var x, y;

            if (e.type === 'touchmove') {
                x = e.touches[0].clientX - canvas.offsetLeft;
                y = e.touches[0].clientY - canvas.offsetTop;
            } else {
                x = e.clientX - canvas.offsetLeft;
                y = e.clientY - canvas.offsetTop;
            }

            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath();
        }

        document.getElementById('clear').addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        document.getElementById('save').addEventListener('click', function() {
            var dataURL = canvas.toDataURL();
        });
    </script>
</body>
</html>
