<style>
    body .filament-form input, 
body .filament-form label, 
body .filament-form .fi-input, 
body .filament-form .fi-label, 

body {
    background-color: #00573e !important; 
    margin: 0;
    height: 100%;
}
main {
        position: center; 
        max-width: 500px;
        
    }


@media screen and (min-width: 1200px) {
    main {
        position: absolute; 
        right:100px;
        max-width: 500px;
        
        
        }

    body {
    background-image: url('/bg.png') !important;
    background-size: cover !important;          /* Resizes the image to cover the whole screen */
    background-position: center !important;     /* Centers the image */
    background-repeat: no-repeat !important;    /* Prevents tiling */
    background-attachment: fixed !important;  
    
    }
    
}

   

    .fi-fo-field-wrp-label
    {
        color:black !important;
        
    }

    
    .fi-logo {
        display: none !important;
        position: right;
        left: 100px;
        font-size: 3em;
        color: cornsilk;
    }

    
}


</style>

<div class="text-xs text-center">
    Copyright &copy; {{ date('Y') }} College of Public Affairs and Development - University of the Philippines Los Baños. All rights reserved.
</div>