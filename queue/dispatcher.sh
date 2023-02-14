#!/bin/bash

# Quelle und Zielverzeichnisse definieren
src_dir="inbox"
dest_dir1="queue1"
dest_dir2="queue2"
dest_dir3="queue3"

# Älteste Datei im Quellverzeichnis finden
oldest_file=$(ls -t "$src_dir" | tail -1)

# Anzahl der Dateien in jedem Zielverzeichnis ermitteln
count1=$(ls "$dest_dir1" | wc -l)
count2=$(ls "$dest_dir2" | wc -l)
count3=$(ls "$dest_dir3" | wc -l)

# Verzeichnis mit der geringsten Anzahl von Dateien auswählen
if [ $count1 -le $count2 ] && [ $count1 -le $count3 ]; then
  dest_dir=$dest_dir1
elif [ $count2 -le $count1 ] && [ $count2 -le $count3 ]; then
  dest_dir=$dest_dir2
else
  dest_dir=$dest_dir3
fi

# Kopieren der Datei
mv "$src_dir/$oldest_file" "$dest_dir/$oldest_file"
