<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
?>

<div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Edit your account</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label>Username</label>
                  <input type="text" class="form-control" id="inputUsername" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label>Name</label>
                  <input type="text" class="form-control" id="inputName" placeholder="Enter name">
                </div>
                <div class="form-group">
                    <label>Surname</label>
                  <input type="text" class="form-control" id="inputSurname" placeholder="Enter surname">
                </div>
                <div class="form-group">
                    <label>Email</label>
                  <input type="email" class="form-control" id="inputEmail" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label>Repeat password</label>
                  <input type="password" class="form-control" id="inputPasswordRepeat" placeholder="Enter password again">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Profile picture</label>
                  <input type="file" id="exampleInputFile">

                  <p class="help-block">Must be less than 300kb in size and in one of the following formats: jpg, png.</p>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
</div>