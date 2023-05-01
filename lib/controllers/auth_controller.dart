import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_fonts/google_fonts.dart';
import '../constants.dart';
import '../enum.dart';
import '../models/user_data.dart';
import '../providers/data_provider.dart';
import '../routes/app_pages.dart';

class AuthController extends GetxController {
  // static AuthController instance = Get.find();

  final _authStatus = AuthStatus.notDetermined.obs;
  AuthStatus get authStatus => _authStatus.value;
  set authStatus(value) => _authStatus.value = value;

  final _token = ''.obs;
  String get token => _token.value;
  set token(value) => _token.value = value;

  final _userRole = ''.obs;
  String get userRole => _userRole.value;
  set userRole(value) => _userRole.value = value;

  final DataProvider _dataProvider = DataProvider();

  final _isLoading = false.obs;
  bool get isLoading => _isLoading.value;
  set isLoading(value) => _isLoading.value = value;

  final _isSigningIn = false.obs;
  get isSigningIn => _isSigningIn.value;

  final _currentAccount = UserModel().obs;
  UserModel? get currentAccount => _currentAccount.value;

  // get userName => _user.value?.displayName;

  @override
  void onInit() {
    super.onInit();
  }

  @override
  void onReady() async {
    super.onReady();
    _initialScreen(authStatus);
    ever(_authStatus, _initialScreen);
  }

  @override
  void onClose() {
    super.onClose();
  }

  _initialScreen(AuthStatus status) async {
    if (status == AuthStatus.signedIn) {
      Get.offAllNamed(Routes.HOME);
      // _currentAccount.close();
    } else {
      Get.offAllNamed(Routes.SIGNIN);
    }
    // } else {
    //   Get.offAllNamed(Routes.SPLASH);
    // }
  }

  void login(String email, password) async {
    isLoading = true;
    _dataProvider.login(username: email, password: password).then((value) {
      print('RESPONSE: $value');
      isLoading = false;
      if (value['success'] == true) {
        // _currentAccount.value = UserModel.fromJson(value['data']);
        // _currentAccount.refresh();
        authStatus = AuthStatus.signedIn;
        token = value['data']['token'];
        userRole = value['data']['role'];
        // Get.offAllNamed(Routes.HOME);
      } else {
        Get.defaultDialog(
            title: 'Erreur de connexion',
            middleText:
                'Une erreur est survenue lors de la connexion au compte: ${value['message']}',
            textConfirm: 'D\'accord',
            middleTextStyle: GoogleFonts.poppins(
              textStyle: const TextStyle(
                  color: Colors.black,
                  fontWeight: FontWeight.w500,
                  fontSize: 13),
            ),
            titleStyle: GoogleFonts.poppins(
              textStyle: const TextStyle(
                  color: Colors.black,
                  fontWeight: FontWeight.w400,
                  fontSize: 13),
            ),
            buttonColor: primaryColor,
            backgroundColor: Colors.white,
            confirmTextColor: Colors.white,
            onConfirm: () {
              Get.back();
            });
      }
    });
  }

  void signOut() async {
    authStatus = AuthStatus.notSignedIn;
    // _currentAccount.close();
  }
}
