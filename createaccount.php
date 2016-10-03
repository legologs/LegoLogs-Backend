<?php
$name = $_GET[ "name" ]; # Get values to set
$password = $_GET[ "password" ];

$file = fopen( "../USER_STORE/userindex.json", "r" ); # Load user index
$accounts = json_decode( fread($file, filesize( "../USER_STORE/userindex.json" ) ), true );
fclose($file);

$alreadyExists = false; # Check to see if username already is in use
foreach($accounts as $val) {
    if( $val == $name )
    {
        $alreadyExists = true;
    }
}

if( !$alreadyExists ) # If username is not in use
{
    $UUID = sizeof( $accounts ) + 1;

    $toAppend = array( "username" => $username, "password" => hash( "sha256", $password ) ); # Create append list

    mkdir( "../USER_STORE/" . $UUID ); # Append user data
    $file = fopen( "../USER_STORE/" . $UUID . "/accountinfo.json", "w" );
    fwrite( $file, json_encode( $toAppend ) );
    fclose( $file );

    $accounts[ $UUID ] = $name; # Create new user index

    $file = fopen( "../USER_STORE/userindex.json", "w" ); # Update userindex
    fwrite( $file, json_encode( $accounts ) );
    fclose( $file );

    $return = array( "returnValue" => "created" ); # Return message
    echo( json_encode( $return ) );
}
else # Account is in use
{
    $return = array( "returnValue" => "account_in_use" ); # Return error message
    echo( json_encode( $return ) );
}
?>