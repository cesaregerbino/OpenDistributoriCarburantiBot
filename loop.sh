#!/bin/bash
while [ -f control.txt ]  
do 
 ./command.sh &  
sleep 5 
done
