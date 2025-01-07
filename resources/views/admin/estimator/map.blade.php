<div class="fence_draw" style="display: block; overflow-y:auto;">
    <div id="map"></div>

    <div class="sidebar">
        <div id="search_contain">
            <input type="text" id="search_box" />
            <button type="button" class="button" id="search_btn">Search</button>
        </div>
        <div class="nav_btn">
            <button type="button" class="btn btn_left" id="move_left_btn">
                <i class="fa fa-arrow-left"></i>
            </button>
            <button type="button" class="btn btn_up" id="move_top_btn">
                <i class="fa fa-arrow-up"></i>
            </button>
            <button type="button" class="btn btn_right" id="move_bottom_btn">
                <i class="fa fa-arrow-down"></i>
            </button>
            <button type="button" class="btn btn_top" id="move_right_btn">
                <i class="fa fa-arrow-right"></i>
            </button>
        </div>
        <div id="feet_contain">
            <h5 class="feet_label">Total Feet <span id="feet">0.00</span></h5>
        </div>
        <button type="button" class="button" id="draw_fence">Add Fence</button>
        <div class="btn_contain">
            <button type="button" class="button" id="home_btn">Back To Home</button>
            <button type="button" class="button" id="undo_btn">Undo</button>
        </div>
        <button type="button" class="button" id="submit_btn" style="width: 100%; display: none">
            Submit
        </button>
        
         {{-- info text --}}
        <div class="info_text">
            <div class="container">
                <div class="card">
                  <div class="card-header text-center">
                    <h2 class="h3 mb-0">Instructions</h2>
                  </div>
                  <div class="card-body">
                    <ol class="mb-0">
                      <li>Enter your address in the search bar above.</li>
                      <li>Click Search.</li>
                      <li>Click add fence.</li>
                      <li>Draw your fence as accurately as possible.</li>
                      <li>Click submit and proceed to the next step.</li>
                    </ol>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?key=AIzaSyCLwLUlZEALsUUhJ0bOmuu_ciYvWnuhsCc&libraries=geometry,drawing,places">

</script>

<script type="text/javascript" src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <script>
        const Canvas2Image = (function () {
    // check if support sth.
    const $support = (function () {
        const canvas = document.createElement("canvas"),
            ctx = canvas.getContext("2d");

        return {
            canvas: !!ctx,
            imageData: !!ctx.getImageData,
            dataURL: !!canvas.toDataURL,
            btoa: !!window.btoa,
        };
    })();

    const downloadMime = "image/octet-stream";

    function scaleCanvas(canvas, width, height) {
        const w = canvas.width,
            h = canvas.height;
        if (width === undefined) {
            width = w;
        }
        if (height === undefined) {
            height = h;
        }

        let retCanvas = document.createElement("canvas");
        let retCtx = retCanvas.getContext("2d");
        retCanvas.width = width;
        retCanvas.height = height;
        retCtx.drawImage(canvas, 0, 0, w, h, 0, 0, width, height);
        return retCanvas;
    }

    function getDataURL(canvas, type, width, height) {
        canvas = scaleCanvas(canvas, width, height);
        return canvas.toDataURL(type);
    }

    // save file to local with file name and file type
    function saveFile(strData, fileType, fileName = "name") {
        // document.location.href = strData;
        let saveLink = document.createElement("a");
        // download file name
        saveLink.download = fileName + "." + fileType;
        // download file data
        saveLink.href = strData;
        // start download
        saveLink.click();
    }

    function genImage(strData) {
        let img = document.createElement("img");
        img.src = strData;
        return img;
    }

    function fixType(type) {
        type = type.toLowerCase().replace(/jpg/i, "jpeg");
        const r = type.match(/png|jpeg|bmp|gif/)[0];
        return "image/" + r;
    }

    function encodeData(data) {
        if (!window.btoa) {
            // eslint-disable-next-line no-throw-literal
            throw "btoa undefined";
        }
        let str = "";
        if (typeof data == "string") {
            str = data;
        } else {
            for (let i = 0; i < data.length; i++) {
                str += String.fromCharCode(data[i]);
            }
        }

        return btoa(str);
    }

    function getImageData(canvas) {
        const w = canvas.width,
            h = canvas.height;
        return canvas.getContext("2d").getImageData(0, 0, w, h);
    }

    function makeURI(strData, type) {
        return "data:" + type + ";base64," + strData;
    }

    /**
     * create bitmap image
     * 按照规则生成图片响应头和响应体
     */
    const genBitmapImage = function (oData) {
        //
        // BITMAPFILEHEADER: http://msdn.microsoft.com/en-us/library/windows/desktop/dd183374(v=vs.85).aspx
        // BITMAPINFOHEADER: http://msdn.microsoft.com/en-us/library/dd183376.aspx
        //

        const biWidth = oData.width;
        const biHeight = oData.height;
        const biSizeImage = biWidth * biHeight * 3;
        const bfSize = biSizeImage + 54; // total header size = 54 bytes

        //
        //  typedef struct tagBITMAPFILEHEADER {
        //  	WORD bfType;
        //  	DWORD bfSize;
        //  	WORD bfReserved1;
        //  	WORD bfReserved2;
        //  	DWORD bfOffBits;
        //  } BITMAPFILEHEADER;
        //
        const BITMAPFILEHEADER = [
            // WORD bfType -- The file type signature; must be "BM"
            0x42,
            0x4d,
            // DWORD bfSize -- The size, in bytes, of the bitmap file
            bfSize & 0xff,
            (bfSize >> 8) & 0xff,
            (bfSize >> 16) & 0xff,
            (bfSize >> 24) & 0xff,
            // WORD bfReserved1 -- Reserved; must be zero
            0,
            0,
            // WORD bfReserved2 -- Reserved; must be zero
            0,
            0,
            // DWORD bfOffBits -- The offset, in bytes, from the beginning of the BITMAPFILEHEADER structure to the bitmap bits.
            54,
            0,
            0,
            0,
        ];

        //
        //  typedef struct tagBITMAPINFOHEADER {
        //  	DWORD biSize;
        //  	LONG  biWidth;
        //  	LONG  biHeight;
        //  	WORD  biPlanes;
        //  	WORD  biBitCount;
        //  	DWORD biCompression;
        //  	DWORD biSizeImage;
        //  	LONG  biXPelsPerMeter;
        //  	LONG  biYPelsPerMeter;
        //  	DWORD biClrUsed;
        //  	DWORD biClrImportant;
        //  } BITMAPINFOHEADER, *PBITMAPINFOHEADER;
        //
        const BITMAPINFOHEADER = [
            // DWORD biSize -- The number of bytes required by the structure
            40,
            0,
            0,
            0,
            // LONG biWidth -- The width of the bitmap, in pixels
            biWidth & 0xff,
            (biWidth >> 8) & 0xff,
            (biWidth >> 16) & 0xff,
            (biWidth >> 24) & 0xff,
            // LONG biHeight -- The height of the bitmap, in pixels
            biHeight & 0xff,
            (biHeight >> 8) & 0xff,
            (biHeight >> 16) & 0xff,
            (biHeight >> 24) & 0xff,
            // WORD biPlanes -- The number of planes for the target device. This value must be set to 1
            1,
            0,
            // WORD biBitCount -- The number of bits-per-pixel, 24 bits-per-pixel -- the bitmap
            // has a maximum of 2^24 colors (16777216, Truecolor)
            24,
            0,
            // DWORD biCompression -- The type of compression, BI_RGB (code 0) -- uncompressed
            0,
            0,
            0,
            0,
            // DWORD biSizeImage -- The size, in bytes, of the image. This may be set to zero for BI_RGB bitmaps
            biSizeImage & 0xff,
            (biSizeImage >> 8) & 0xff,
            (biSizeImage >> 16) & 0xff,
            (biSizeImage >> 24) & 0xff,
            // LONG biXPelsPerMeter, unused
            0,
            0,
            0,
            0,
            // LONG biYPelsPerMeter, unused
            0,
            0,
            0,
            0,
            // DWORD biClrUsed, the number of color indexes of palette, unused
            0,
            0,
            0,
            0,
            // DWORD biClrImportant, unused
            0,
            0,
            0,
            0,
        ];

        const iPadding = (4 - ((biWidth * 3) % 4)) % 4;

        const aImgData = oData.data;

        let strPixelData = "";
        const biWidth4 = biWidth << 2;
        let y = biHeight;
        const fromCharCode = String.fromCharCode;

        do {
            const iOffsetY = biWidth4 * (y - 1);
            let strPixelRow = "";
            for (let x = 0; x < biWidth; x++) {
                const iOffsetX = x << 2;
                strPixelRow +=
                    fromCharCode(aImgData[iOffsetY + iOffsetX + 2]) +
                    fromCharCode(aImgData[iOffsetY + iOffsetX + 1]) +
                    fromCharCode(aImgData[iOffsetY + iOffsetX]);
            }

            for (let c = 0; c < iPadding; c++) {
                strPixelRow += String.fromCharCode(0);
            }

            strPixelData += strPixelRow;
        } while (--y);

        return (
            encodeData(BITMAPFILEHEADER.concat(BITMAPINFOHEADER)) +
            encodeData(strPixelData)
        );
    };

    /**
     * saveAsImage
     * @param canvas canvasElement
     * @param width {String} image type
     * @param height {Number} [optional] png width
     * @param type {string} [optional] png height
     * @param fileName {String} image name
     */
    const saveAsImage = function (canvas, width, height, type, fileName) {
        // save file type
  
        const fileType = type;
        if ($support.canvas && $support.dataURL) {
            if (typeof canvas == "string") {
                canvas = document.getElementById(canvas);
            }
            if (type === undefined) {
                type = "png";
            }
            type = fixType(type);
            // const data = getImageData(scaleCanvas(canvas, width, height));
            // const strData = genBitmapImage(data);
            //     // use new parameter: fileType
            //   makeURI(strData, downloadMime);
            if (/bmp/.test(type)) {
                const data = getImageData(scaleCanvas(canvas, width, height));
                const strData = genBitmapImage(data);
                // use new parameter: fileType
                saveFile(makeURI(strData, downloadMime), fileType, fileName);
            }
            else {
               
                const strData = getDataURL(canvas, type, width, height);
                return strData.replace(type, downloadMime);
                // use new parameter: fileType
                saveFile(strData.replace(type, downloadMime), fileType, fileName);
            }
        }
    };

    const convertToImage = function (canvas, width, height, type) {
        if ($support.canvas && $support.dataURL) {
            if (typeof canvas == "string") {
                canvas = document.getElementById(canvas);
            }
            if (type === undefined) {
                type = "png";
            }
            type = fixType(type);

            if (/bmp/.test(type)) {
                const data = getImageData(scaleCanvas(canvas, width, height));
                const strData = genBitmapImage(data);
                return genImage(makeURI(strData, "image/bmp"));
            } else {
                const strData = getDataURL(canvas, type, width, height);
                return genImage(strData);
            }
        }
    };

    return {
        saveAsImage: saveAsImage,
        saveAsPNG: function (canvas, width, height, fileName) {
            return saveAsImage(canvas, width, height, "png", fileName);
        },

       
        saveAsJPEG: function (canvas, width, height, fileName) {
            return saveAsImage(canvas, width, height, "jpeg", fileName);
        },
        saveAsGIF: function (canvas, width, height, fileName) {
            return saveAsImage(canvas, width, height, "gif", fileName);
        },
        saveAsBMP: function (canvas, width, height, fileName) {
            return saveAsImage(canvas, width, height, "bmp", fileName);
        },

        convertToImage: convertToImage,
        convertToPNG: function (canvas, width, height) {
            return convertToImage(canvas, width, height, "png");
        },
        convertToJPEG: function (canvas, width, height) {
            return convertToImage(canvas, width, height, "jpeg");
        },
        convertToGIF: function (canvas, width, height) {
            return convertToImage(canvas, width, height, "gif");
        },
        convertToBMP: function (canvas, width, height) {
            return convertToImage(canvas, width, height, "bmp");
        },
    };
})();
    </script>


<script>
    var feet = 10;
    var g = google.maps;
    let marker;
    var latlng = {
        lat: 40,
        lng: -102
    };
    var line_array = [];
    var line;
    var line_no = 1;
    var line_length_array = [];

    function scrollLock(add = true) {
        var mapbody = document.querySelector(".fence_draw");
        if (add) {
            mapbody.classList.add("map-contain");
            parentWindow.postMessage("add_map", "*");
        } else {
            mapbody.classList.remove("map-contain");
            parentWindow.postMessage("remove_map", "*");
        }
    }

    function initialize() {
        var myOptions = {
            zoom: 5,
            center: latlng,
            tilt: 0,
            gestureHandling: "none",
            mapTypeId: google.maps.MapTypeId.HYBRID,
        };
        var map = new google.maps.Map(document.getElementById("map"), myOptions);


        //registerMapDragHandler(map);

        const geocoder = new google.maps.Geocoder();
        document.getElementById("search_btn").addEventListener("click", () => {

            if (document.getElementById("search_box").value.trim() == "") {
                alert("search address first");
                return;
            }


            document.getElementById("search_contain").style.display = "none";
            document.getElementById("draw_fence").style.display = "block";

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                document.querySelector(".fence_draw.map-contain #map").style = "height: 60vh !important";
                document.querySelector(".fence_draw.map-contain .sidebar").style = "height: 40vh !important";
                document.querySelector(".fence_draw .button").style = "display: block";
            }
            geocodeAddress(geocoder, map);
        });

        document.getElementById("draw_fence").addEventListener("click", () => {
            scrollLock();
            document.querySelector(".fence_draw").style = "overflow-y: none;";
            alert("Please Draw Fence Now");
            try {
                marker.setMap(null);
            } catch (error) {

            }

            document.getElementById("draw_fence").style.display = "none";
            document.getElementById("feet_contain").style = "display: block;";
            document.getElementById("home_btn").style.display = "block";
            document.getElementById("undo_btn").style.display = "block";
            document.getElementById("submit_btn").style.display = "block";

            registerMapDragHandler(map);
        });

        document.getElementById("home_btn").addEventListener("click", () => {
            $('#prev').click();
        });

        document.getElementById("undo_btn").addEventListener("click", () => {
            if (line_length_array.length > 0) {
                var line_name = line_array[line_array.length - 1];
                line_name.setMap(null);
                line_array.pop();
                if (line_length_array.length < 2) {
                    document.getElementById("feet").innerHTML = "0.00";
                    line_length_array.pop();
                    total_length = 0;
                } else {
                    var total = document.getElementById("feet").innerHTML;
                    total = total - line_length_array[line_length_array.length - 1];
                    line_length_array.pop();
                    document.getElementById("feet").innerHTML = total.toFixed(2);
                }
            }
        });

        document.getElementById("move_top_btn").addEventListener("click", () => {
            var lt = latlng.lat;
            var lg = latlng.lng;
            if (lt > 70) {
                lt = 5;
            }
            lt = lt + 0.0001;
            latlng = {
                lat: lt,
                lng: lg
            };
            map.panTo(latlng);
        });

        document.getElementById("move_bottom_btn").addEventListener("click", () => {
            var lt = latlng.lat;
            var lg = latlng.lng;
            if (lt < 5) {
                lt = 85;
            }
            lt = lt - 0.0001;
            latlng = {
                lat: lt,
                lng: lg
            };
            map.panTo(latlng);
        });

        document.getElementById("move_left_btn").addEventListener("click", () => {
            var lt = latlng.lat;
            var lg = latlng.lng;
            lg = lg - 0.0001;
            latlng = {
                lat: lt,
                lng: lg
            };
            map.panTo(latlng);
        });

        document.getElementById("move_right_btn").addEventListener("click", () => {
            var lt = latlng.lat;
            var lg = latlng.lng;
            lg = lg + 0.0001;
            latlng = {
                lat: lt,
                lng: lg
            };
            map.panTo(latlng);
        });
    }
    initialize();

    function geocodeAddress(geocoder, resultsMap) {
        const address = document.getElementById("search_box").value;
        geocoder.geocode({
            address: address
        }, (results, status) => {
            if (status === "OK") {
                var lt = results[0].geometry.location.lat();
                var lg = results[0].geometry.location.lng();
                latlng.lat = lt;
                latlng.lng = lg;

                resultsMap.setCenter(results[0].geometry.location);
                resultsMap.setZoom(20);
                marker = new google.maps.Marker({
                    map: resultsMap,
                    position: results[0].geometry.location,
                });
            }
        });
    }

    function registerMapDragHandler(map) {
        total_length = 0;
        map.setOptions({
            draggableCursor: "crosshair"
        });
        g.event.addListener(map, "mousedown", function(event) {
            createLineBeingDragged(map, event.latLng);
        });

        const lineSymbol = {
            path: "M 0,-1 0,1",
            strokeOpacity: 1,
            scale: 3,
        };

        function createLineBeingDragged(map, pos) {
            lineOption = {
                map: map,
                path: [pos, pos],
                strokeColor: "#fff",
                cursor: "crosshair",
                strokeOpacity: "0",
                icons: [{
                    icon: lineSymbol,
                    offset: "0",
                    repeat: "25px",
                }, ],
                geodesic: true,
                zIndex: -1,
            };
            line = new g.Polyline(lineOption);

            line_array.push(line);

            g.event.addListener(map, "mousemove", function(event) {
                line.getPath().setAt(1, event.latLng);
            });

            g.event.addListener(line, "mouseup", function(event) {
                g.event.clearListeners(map, "mousemove");
                g.event.clearListeners(line, "click");

                line.setOptions({
                    strokeColor: "#ffcc00"
                });
                line.setOptions({
                    strokeWeight: 10
                });
                line.setOptions({
                    strokeOpacity: "1"
                });
                line.setOptions({
                    clickable: false
                });
                console.log(line.getPath());
                var length = g.geometry.spherical.computeLength(line.getPath());
                length = length * 3.28084;
                line_length_array.push(length);
                total_length += length;
                document.getElementById("feet").innerHTML = total_length.toFixed(2);
            });

            google.maps.event.addListener(line, "mouseover", function(e) {
                map.setOptions({
                    draggableCursor: "crosshair"
                });
            });
        }
    }


    document.querySelector("#submit_btn").addEventListener("click", gotoURL);

    // function screenshort() { 
    //     html2canvas($("#map"), {
    //         useCORS: true,
    //         onrendered: function(canvas) {
    //             theCanvas = canvas;
    //             document.body.appendChild(canvas);
    //             Canvas2Image.saveAsPNG(canvas); 
    //             $("#img-out").append(canvas);
    //         }
    //     });
    // }
    function screenshort() {
       
        html2canvas($("#map"), {
            useCORS: true,
//                        allowTaint:true,
            onrendered: function(canvas) {
                // theCanvas = canvas;
                // document.body.appendChild(canvas);
                // Convert and download as image 
                let $img = Canvas2Image.saveAsPNG(canvas); 
                let file  = base64ToBlob($img,'image/png');
                sendBase64Image(file)
                console.log(file);
               // $("#img-out").append(canvas);
                // Clean up 
                //document.body.removeChild(canvas);
            }
        });
          
        }

        function base64ToBlob(base64, mime) {
            let byteString = atob(base64.split(',')[1]);
            let ab = new ArrayBuffer(byteString.length);
            let ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], {type: mime});
        }

        function sendBase64Image(blob) {
            let formData = new FormData();
            formData.append('file', blob, 'screenshot.png');
            formData.append('uuid', selection.estimator_id);
            var url = '{{ route('estimate.save.image') }}';
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(response => {
                selection.image = selection.estimator_id + '.png';
            }).then(data => {
                console.log('Success:', data);
            }).catch((error) => {
                console.error('Error:', error);
            });
        }

    function gotoURL() {
        var feetht = document.getElementById("feet").innerHTML;
        if (feetht) {
            screenshort();
            setTimeout(() => {
                feet = feetht;
            selection.feet = feet;
            $('.feet').val(feet);
            $('#next').click();
            }, 800);
            //   window.location.href = url;
        }
    }

    var search_box = document.getElementById("search_box");
    var sessionToken = new google.maps.places.AutocompleteSessionToken();
    var autocomplete = new google.maps.places.Autocomplete(search_box,{'sessiontoken':sessionToken});
</script>


<script>
     // zooms out the screen for IOS devices when input is focused, after input is deselected
     document.addEventListener('focus', function(event) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
        }
    }, true);

    document.addEventListener('blur', function(event) {
        if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') {
            document.querySelector('meta[name="viewport"]').setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=1');
        }
    }, true);

    // PREVENT MAP SCROLLING

    // Get a reference to your map element
    var mapElement = document.getElementById('map');

    // Add touch event listeners to the map element
    mapElement.addEventListener('touchstart', function(e) {
    // Prevent the default touchstart behavior (which can cause page scroll)
    e.preventDefault();
    }, { passive: false });

    mapElement.addEventListener('touchmove', function(e) {
    // Prevent the default touchmove behavior (which can cause page scroll)
    e.preventDefault();
    }, { passive: false });

</script>

<style>
    input, select, textarea {
        font-size: 16px;
    }
</style>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

<style type="text/css">
    #map {
        height: 100%;
        width: 100%;
    }

    .fence_draw .sidebar {
        background-color: #fff;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        padding: 15px;
        width: 25%;
        height: 100%;
    }

    .fence_draw .sidebar {
        /*width: 100%;*/
    }

    #search_box {
        display: block;
        padding: 6px 10px;
        font-size: 16px;
        width: 100%;
        background-color: transparent;
        border: 1px solid #4687ce;
        border-radius: 8px;
    }

    .fence_draw .button {
        display: hidden;
        padding: 8px 0;
        width: 100%;
        cursor: pointer;
        border: none;
        background-color: {{ setting('estimator_primary_color', $loc->id) ?? '#ED2846' }};
        color: #fff;
        border-radius: 20px;
        margin: 10px 0;
        font-size: 16px;
    }

    .fence_draw .button:focus {
        outline: none;
    }

    #feet_contain {
        display: none;
    }

    .feet_label {
        font-size: 20px;
        text-align: center;
        margin: 0;
        margin-top: 30px;
        color: #006666;
    }

    #feet {
        font-size: 20px;
        text-align: center;
        margin: 0;
        color: #006666;
    }

    .fence_draw #draw_fence,
    .fence_draw #undo_btn,
    .fence_draw #home_btn {
        display: none;
    }

    .fence_draw #search_btn,
    .fence_draw #draw_fence,
    .fence_draw #search_box {
        /*display: none;*/
    }

    .fence_draw .nav_btn {
        text-align: center;
        margin: 10px 0;
    }

    .fence_draw .btn {
        position: relative;
        width: 30px;
        height: 30px;
        cursor: pointer;
        border: none;
        background-color: #fff;
        color: #fff;
        border-radius: 50%;
    }

    .fence_draw .btn .fa {
        color: #4687ce;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .fence_draw .btn:focus {
        outline: none;
    }

    @media screen and (max-width: 850px) {


        .fence_draw.map-contain #map {
            height: 40vh !important;
        }

        .fence_draw.map-contain {
            background: #fff !important;
        }

        .fence_draw.map-contain .sidebar {
            height: 60vh !important;
            width: 100% !important;
            padding: 8px;
            overflow-y: auto;
            /*position: unset;*/
            top: unset !important;
            left: unset !important;
            width: 100% !important;
        }

        .fence_draw .btn_contain {
            display: flex;
            margin-top: 5px;
        }

        .fence_draw .btn_contain button {
            width: 100%;
            margin: auto;
        }


        .fence_draw .btn {
            position: relative;
            width: 80px;
            height: 80px;
            font-size: 40px; /* Adjust the size as needed */
            cursor: pointer;
            border: none;
            background-color: #fff;
            color: #fff;
            border-radius: 50%;
        }

        .feet_label {
            margin-top: 0;
            font-size: 16px;
        }

        #feet {
            font-size: 16px;
        }

        .info_text {
            margin-top: 10px;
        }
    }

    .map-contain {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 9999999999999;
    }
</style>
