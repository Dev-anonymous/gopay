import 'package:get/get.dart';
import '../screens/auth/signin.dart';

import '../screens/auth/signup.dart';
import '../screens/main/main_screen.dart';

part '../routes/app_routes.dart';

class AppPages {
  AppPages._();

  static const INITIAL = Routes.HOME;

  static final routes = [
    GetPage(name: _Paths.HOME, page: () => MainScreen()),
    GetPage(name: _Paths.SIGNIN, page: () => SigninPage()),
    GetPage(name: _Paths.SIGNUP, page: () => SignupPage()),
  ];
}
