#!/bin/bash

source_dir="/usr/share/nginx/html/img"
dest_dir_camera1="/media/madkam/grab_img/camera1_copias"
dest_dir_camera2="/media/madkam/grab_img/camera2_copias"
dest_dir_images="/media/madkam/cp_total"
dest_dir_grabacion1="/media/madkam/grab_img/grabacion1_copias"
dest_dir_grabacion2="/media/madkam/grab_img/grabacion2_copias"

rsync -av --ignore-existing "$source_dir/camera1"* "$dest_dir_camera1/"

rsync -av --ignore-existing "$source_dir/camera2"* "$dest_dir_camera2/"

rsync -av --ignore-existing "$source_dir/"* "$dest_dir_images/"

rsync -av --ignore-existing "$source_dir/grabacion1"* "$dest_dir_grabacion1/"

rsync -av --ignore-existing "$source_dir/grabacion2"* "$dest_dir_grabacion2/"

