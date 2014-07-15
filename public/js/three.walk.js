   
function firstTree() {   
    
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);

    var renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

    var geometry = new THREE.CubeGeometry(1,1,1);
    var material = new THREE.MeshBasicMaterial({color: 0x00ff00});
    var cube = new THREE.Mesh(geometry, material);
    scene.add(cube);

    camera.position.z = 5;

    var render = function () {
            requestAnimationFrame(render);

            cube.rotation.x += 0.1;
            cube.rotation.y += 0.1;

            renderer.render(scene, camera);
    };

    render();
}


function treeHouseAnimation() {
    
      var scene, camera, renderer;
        
        init();
        animate();
        
        function init() {
            scene = new THREE.Scene();
            var width  = window.innerWidth,
                height = window.innerHeight;
            
            renderer = new THREE.WebGLRenderer({atialias: true});
            renderer.setSize(width, height);
            document.body.appendChild(renderer.domElement); //canvas
            camera = new THREE.PerspectiveCamera(45, width/height, 0.1, 10000);
            camera.position.set(0,6,0);
            scene.add(camera);
            
            window.addEventListener('resize', function() {
               var width = window.innerWidth,
                  height = window.innerHeight;
                  renderer.setSize(width,height);
                  camera.aspect = width/height;
                  camera.updateProjectionMatrix();
            });
             
            renderer.setClearColorHex(0x333F47, 1); //background
            var light = new THREE.PointLight(0xffffff); //light
            light.position.set(-100,200,100);
            scene.add(light);
            
            var loader = new THREE.JSONLoader();
            loader.load('/js/models/treehouse_logo.js', function(geometry) {
               var material = new THREE.MeshLambertMaterial({color: 0x55B663});
               var mesh = new THREE.Mesh(geometry, material);
               scene.add(mesh);
            });
            //czy ma byc var ?
            controls = new THREE.OrbitControls(camera,renderer.domElement);
//            controls = new THREE.FirstPersonControls(camera,renderer.domElement);
                
        }
        
        function animate() {
            
            //read
            requestAnimationFrame(animate);            
            //render scene
            renderer.render(scene,camera);
            controls.update();
            
        }
    
}

function cubeAnimation() {
    var camera, scene, renderer;
    var geometry, material, mesh;

    init();
    animate();

    function init() {

        camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 1, 10000 );
        camera.position.z = 1000;

        scene = new THREE.Scene();

        geometry = new THREE.CubeGeometry( 200, 200, 200 );
        material = new THREE.MeshBasicMaterial( { color: 0xff0000, wireframe: true } );

        mesh = new THREE.Mesh( geometry, material );
        var light = new THREE.AmbientLight( 0x404040 ); // soft white light
        scene.add( light );
        scene.add( mesh );

        renderer = new THREE.CanvasRenderer();
        renderer.setSize( window.innerWidth, window.innerHeight );

        document.body.appendChild( renderer.domElement );

    }

    function animate() {

        // note: three.js includes requestAnimationFrame shim
        requestAnimationFrame( animate );

        mesh.rotation.x += 0.05;
        mesh.rotation.y += 0.02;

        renderer.render( scene, camera );

    }
}

function cubeSpinAniamtion() {
    
    var container, stats;

    var camera, scene, renderer;

    var cube, plane;

    var targetRotation = 0;
    var targetRotationOnMouseDown = 0;

    var mouseX = 0;
    var mouseXOnMouseDown = 0;

    var windowHalfX = window.innerWidth / 2;
    var windowHalfY = window.innerHeight / 2;

    init();
    animate();

    function init() {

            container = document.createElement( 'div' );
            document.body.appendChild( container );

            var info = document.createElement( 'div' );
            info.style.position = 'absolute';
            info.style.top = '70px';
            info.style.width = '100%';
            info.style.textAlign = 'center';
            info.innerHTML = 'Drag to spin the cube';
            container.appendChild( info );

            camera = new THREE.PerspectiveCamera( 70, window.innerWidth / window.innerHeight, 1, 1000 );
            camera.position.y = 150;
            camera.position.z = 500;

            scene = new THREE.Scene();

            // Cube

            var geometry = new THREE.CubeGeometry( 200, 200, 200 );

            for ( var i = 0; i < geometry.faces.length; i += 2 ) {

                    var hex = Math.random() * 0xffffff;
                    geometry.faces[ i ].color.setHex( hex );
                    geometry.faces[ i + 1 ].color.setHex( hex );

            }

            var material = new THREE.MeshBasicMaterial( { vertexColors: THREE.FaceColors, overdraw: 0.5 } );

            cube = new THREE.Mesh( geometry, material );
            cube.position.y = 150;
            scene.add( cube );

            // Plane

            var geometry = new THREE.PlaneGeometry( 200, 200 );
            geometry.applyMatrix( new THREE.Matrix4().makeRotationX( - Math.PI / 2 ) );

            var material = new THREE.MeshBasicMaterial( { color: 0xe0e0e0, overdraw: 0.5 } );

            plane = new THREE.Mesh( geometry, material );
            scene.add( plane );

            renderer = new THREE.CanvasRenderer();
            renderer.setSize( window.innerWidth, window.innerHeight );

            container.appendChild( renderer.domElement );

            stats = new Stats();
            stats.domElement.style.position = 'absolute';
            stats.domElement.style.top = '60px';
            container.appendChild( stats.domElement );

            document.addEventListener( 'mousedown', onDocumentMouseDown, false );
            document.addEventListener( 'touchstart', onDocumentTouchStart, false );
            document.addEventListener( 'touchmove', onDocumentTouchMove, false );

            //

            window.addEventListener( 'resize', onWindowResize, false );

    }

    function onWindowResize() {

            windowHalfX = window.innerWidth / 2;
            windowHalfY = window.innerHeight / 2;

            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();

            renderer.setSize( window.innerWidth, window.innerHeight );

    }

    //

    function onDocumentMouseDown( event ) {

            event.preventDefault();

            document.addEventListener( 'mousemove', onDocumentMouseMove, false );
            document.addEventListener( 'mouseup', onDocumentMouseUp, false );
            document.addEventListener( 'mouseout', onDocumentMouseOut, false );

            mouseXOnMouseDown = event.clientX - windowHalfX;
            targetRotationOnMouseDown = targetRotation;

    }

    function onDocumentMouseMove( event ) {

            mouseX = event.clientX - windowHalfX;

            targetRotation = targetRotationOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.02;

    }

    function onDocumentMouseUp( event ) {

            document.removeEventListener( 'mousemove', onDocumentMouseMove, false );
            document.removeEventListener( 'mouseup', onDocumentMouseUp, false );
            document.removeEventListener( 'mouseout', onDocumentMouseOut, false );

    }

    function onDocumentMouseOut( event ) {

            document.removeEventListener( 'mousemove', onDocumentMouseMove, false );
            document.removeEventListener( 'mouseup', onDocumentMouseUp, false );
            document.removeEventListener( 'mouseout', onDocumentMouseOut, false );

    }

    function onDocumentTouchStart( event ) {

            if ( event.touches.length === 1 ) {

                    event.preventDefault();

                    mouseXOnMouseDown = event.touches[ 0 ].pageX - windowHalfX;
                    targetRotationOnMouseDown = targetRotation;

            }

    }

    function onDocumentTouchMove( event ) {

            if ( event.touches.length === 1 ) {

                    event.preventDefault();

                    mouseX = event.touches[ 0 ].pageX - windowHalfX;
                    targetRotation = targetRotationOnMouseDown + ( mouseX - mouseXOnMouseDown ) * 0.05;

            }

    }

    //

    function animate() {

            requestAnimationFrame( animate );

            render();
            stats.update();

    }

    function render() {

            plane.rotation.y = cube.rotation.y += ( targetRotation - cube.rotation.y ) * 0.05;
            renderer.render( scene, camera );

    }


}