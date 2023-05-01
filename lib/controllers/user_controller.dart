import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../constants.dart';
import '../models/user_data.dart';
import '../providers/data_provider.dart';

class UserController extends GetxController {
  DataProvider _dataProvider = DataProvider();

  final _isLoading = false.obs;
  bool get isLoading => _isLoading.value;

  UserController();

  @override
  void onInit() {
    super.onInit();
  }

  @override
  void onReady() async {
    super.onReady();
  }

  @override
  void onClose() {
    super.onClose();
  }

  bool ajouterClient(UserModel userModel) {
    return false;
  }
}
