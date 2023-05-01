import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../../controllers/auth_controller.dart';
import '../../controllers/main_controller.dart';
import '../../enum.dart';
import '../../responsive.dart';
import '../compte/compte.dart';
import '../dashboard/dashboard_screen.dart';
import '../demande_transfert/demande_tranferts.dart';
import '../gestion_stations/stations.dart';
import '../splash.dart';
import '../stations/station.dart';
import '../transactions/transactions.dart';
import 'components/side_menu.dart';

class MainScreen extends StatelessWidget {
  MainController controller = Get.put(MainController());
  AuthController authController = Get.put(AuthController());

  @override
  Widget build(BuildContext context) {
    return (authController.authStatus == AuthStatus.notDetermined)
        ? SplashScreen()
        : Scaffold(
            key: controller.scaffoldKey,
            backgroundColor: Colors.white,
            drawer: SideMenu(),
            body: SafeArea(
                child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // We want this side menu only for large screen
                if (Responsive.isDesktop(context))
                  Expanded(
                    // default flex = 1
                    // and it takes 1/6 part of the screen
                    child: SideMenu(),
                  ),
                Expanded(
                  // It takes 5/6 part of the screen
                  flex: 5,
                  child: Obx(() => _buildScreen()),
                ),
              ],
            )),
          );
  }

  Widget _buildScreen() {
    switch (controller.screenIndex) {
      case 0:
        return DashboardScreen();
      case 1:
        return DemandeTransfertScreen();
      case 2:
        return TransactionsScreen();
      case 3:
        return const Placeholder();
      case 4:
        return MonCompteScreen();
      case 5:
        return GestionStationScreen();
      default:
        return DashboardScreen();
    }
  }
}
