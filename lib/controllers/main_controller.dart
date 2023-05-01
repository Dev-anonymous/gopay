import 'package:flutter/material.dart';
import 'package:get/get.dart';

class MainController extends GetxController {
  final _screenIndex = 0.obs;
  int get screenIndex => _screenIndex.value;

  final _mainTitle = 'Dashboard'.obs;
  String get mainTitle => _mainTitle.value;
  set mainTitle(String value) => _mainTitle.value = value;

  set screenIndex(int value) => _screenIndex.value = value;

  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();

  GlobalKey<ScaffoldState> get scaffoldKey => _scaffoldKey;

  @override
  void onInit() {
    super.onInit();
  }

  @override
  void onReady() {
    super.onReady();
  }

  @override
  void onClose() {
    super.onClose();
  }

  void controlMenu() {
    if (!_scaffoldKey.currentState!.isDrawerOpen) {
      _scaffoldKey.currentState!.openDrawer();
    }
  }
}
