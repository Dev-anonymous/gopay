import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:get/get.dart';

import 'constants.dart';
import 'controllers/auth_controller.dart';
import 'routes/app_pages.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // await Firebase.initializeApp(
  //   options: DefaultFirebaseOptions.currentPlatform,
  // ).then((value) => Get.put(AuthController()));

  await Get.put(AuthController());
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'INTE ECO',
      theme: ThemeData.dark().copyWith(
        scaffoldBackgroundColor: bgColor,
        primaryColor: primaryColor,
        progressIndicatorTheme:
            const ProgressIndicatorThemeData(color: primaryColor),
        textTheme: GoogleFonts.poppinsTextTheme(Theme.of(context).textTheme)
            .apply(bodyColor: Colors.white),
        canvasColor: secondaryColor,
      ),
      // home: MultiProvider(
      //     providers: [
      //       ChangeNotifierProvider(
      //         create: (context) => MenuController(),
      //       ),
      //     ],
      //     child: Obx(
      //         () => controller.isSigningIn ? MainScreen() : SplashScreen())),
      navigatorKey: Get.key,
      getPages: AppPages.routes,
    );
  }
}
