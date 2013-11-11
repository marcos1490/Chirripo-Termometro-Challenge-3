<?php


function automate($firstName, $lastName){
    $descriptorspec = array(
       0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
       1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
       2 => array("file", "./error-output.txt", "a") // stderr is a file to write to
    );
    $process = proc_open('ruby problem.rb', $descriptorspec, $pipes);

    if (is_resource($process)) {
        // $pipes now looks like this:
        // 0 => writeable handle connected to child stdin
        // 1 => readable handle connected to child stdout
        // Any error output will be appended to /tmp/error-output.txt
        
        fwrite($pipes[0], "{$firstName}\n");
        fwrite($pipes[0], "{$lastName}");
       
        fclose($pipes[0]);

        echo ("\n".fgets($pipes[1],4096).$firstName); //get answer
        echo ("\n".fgets($pipes[1],4096).$lastName); //get answer
        echo ("\n".fgets($pipes[1],4096)); //get answer


        fclose($pipes[1]);

        // It is important that you close any pipes before calling
        // proc_close in order to avoid a deadlock
        $return_value = proc_close($process);
    }
}

automate("Cristiam", "Vargas");

?>