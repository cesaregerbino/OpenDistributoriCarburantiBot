#!/bin/sh
#
# >>>>>>> !!! Substitute <BASE_PATH> with the right path in your environment: for example /var/www/html/Telegram !!! <<<<<<
#
echo "*********>> Remove old csv files ...."
echo " "
rm <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/*.csv
sleep 10
#echo "*********>> Get anagrafica_impianti_attivi.csv file ..."
#echo " "
#curl -L -A "Opera" http://www.sviluppoeconomico.gov.it/images/exportCSV/anagrafica_impianti_attivi.csv > anagrafica_impianti_attivi.csv
#sleep 10
#echo "*********>> Get prezzo_alle_8.csv file ..."
#echo " "
#curl -L -A "Opera" http://www.sviluppoeconomico.gov.it/images/exportCSV/prezzo_alle_8.csv > prezzo_alle_8.csv
#sleep 10
#When used without proxy ...
echo "*********>> Get prezzo_alle_8.csv file ..."
echo " "
wget -U "Opera" -O <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/prezzo_alle_8.csv http://www.sviluppoeconomico.gov.it/images/exportCSV/prezzo_alle_8.csv
sleep 10
echo "*********>> Get anagrafica_impianti_attivi.csv file ..."
echo " "
wget -U "Opera" -O <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/anagrafica_impianti_attivi.csv http://www.sviluppoeconomico.gov.it/images/exportCSV/anagrafica_impianti_attivi.csv
sleep 10
#When used behind a proxy ...
#echo "*********>> Get prezzo_alle_8.csv file ..."
#echo " "
#wget -U "Opera" -O <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/prezzo_alle_8.csv http://www.sviluppoeconomico.gov.it/images/exportCSV/prezzo_alle_8.csv  -e use_proxy=yes -e http_proxy=proxy.csi.it:3128
#sleep 10
#echo "*********>> Get anagrafica_impianti_attivi.csv file ..."
#echo " "
#wget -U "Opera" -O <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/anagrafica_impianti_attivi.csv http://www.sviluppoeconomico.gov.it/images/exportCSV/anagrafica_impianti_attivi.csv  -e use_proxy=yes -e http_proxy=proxy.csi.it:3128
#sleep 10
echo "*********>> Remove the first line from anagrafica_impianti_attivi.csv creating anagrafica-temp.csv file ..."
echo " "
sed '1d' <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/anagrafica_impianti_attivi.csv > <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/anagrafica-temp.csv
sleep 10
echo "*********>> Substitute \" in anagrafica-temp.csv creating anagrafica.csv ..."
echo " "
sed 's/"//g' <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/anagrafica-temp.csv  > <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/anagrafica.csv
sleep 10
echo "*********>> Remove the first line from prezzo_alle_8.csv creating prezzo.csv file ..."
echo " "
sed '1d' <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/prezzo_alle_8.csv > <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/prezzo.csv
sleep 10
echo "*********>> Move the current PrezziCarburanti file in PrezziCarburanti-old ..."
echo " "
mv <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/PrezziCarburanti <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/PrezziCarburanti-old
sleep 10
echo "*********>> Create new PrezziCarburanti Spatialite database ..."
echo " "
/usr/local/bin/spatialite <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/PrezziCarburanti < <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/CreaPrezziCarburanti.txt
sleep 10
echo "*********>> Copy PrezziCarburanti Spatialite database in the upper directory ..." 
echo " "
cp <BASE_PATH>/OpenDistributoriCarburantiBot/Dati/PrezziCarburanti <BASE_PATH>/OpenDistributoriCarburantiBot/PrezziCarburanti
