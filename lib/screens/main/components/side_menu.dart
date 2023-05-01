import 'package:google_fonts/google_fonts.dart';
import 'package:gopay/constants.dart';
import 'package:gopay/controllers/auth_controller.dart';
import 'package:gopay/controllers/main_controller.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';

class SideMenu extends StatelessWidget {
  SideMenu({
    Key? key,
  }) : super(key: key);

  MainController controller = Get.put(MainController());
  AuthController authController = Get.put(AuthController());

  @override
  Widget build(BuildContext context) {
    return Obx(() => Drawer(
          backgroundColor: primaryColor,
          child: ListView(
            children: [
              DrawerHeader(
                margin: const EdgeInsets.only(bottom: 0),
                child: Center(
                  child: Text(
                    "GoPay",
                    style: GoogleFonts.poppins(
                      textStyle: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 32),
                    ),
                  ),
                ),
              ),
              Container(
                color: (controller.screenIndex == 0)
                    ? bgColor.withOpacity(0.30)
                    : Colors.transparent,
                child: DrawerListTile(
                  title: "Dashboard",
                  svgSrc: "assets/icons/menu_dashbord.svg",
                  press: () {
                    controller.screenIndex = 0;
                    controller.mainTitle = "Dashboard";
                  },
                ),
              ),
              Container(
                color: (controller.screenIndex == 1)
                    ? bgColor.withOpacity(0.30)
                    : Colors.transparent,
                child: DrawerListTile(
                  title: "Transfert",
                  svgSrc: "assets/icons/send.svg",
                  press: () {
                    controller.screenIndex = 1;
                    controller.mainTitle = "Transfert de l'argent";
                  },
                ),
              ),
              Container(
                color: (controller.screenIndex == 2)
                    ? bgColor.withOpacity(0.30)
                    : Colors.transparent,
                child: DrawerListTile(
                  title: "Transactions",
                  svgSrc: "assets/icons/transaction.svg",
                  press: () {
                    controller.screenIndex = 2;
                    controller.mainTitle = "Toutes mes transactions";
                  },
                ),
              ),
              Container(
                color: (controller.screenIndex == 3)
                    ? bgColor.withOpacity(0.30)
                    : Colors.transparent,
                child: DrawerListTile(
                  title: "Clés API",
                  svgSrc: "assets/icons/key.svg",
                  press: () {
                    controller.screenIndex = 3;
                    controller.mainTitle = "Vos clés API";
                  },
                ),
              ),
              Container(
                color: (controller.screenIndex == 4)
                    ? bgColor.withOpacity(0.30)
                    : Colors.transparent,
                child: DrawerListTile(
                  title: "Mon compte",
                  svgSrc: "assets/icons/person.svg",
                  press: () {
                    controller.screenIndex = 4;
                    controller.mainTitle = "Mon compte";
                  },
                ),
              ),
              // if (authController.currentAccount!.role == "admin")
              //   Container(
              //       color: (controller.screenIndex == 4)
              //           ? bgColor.withOpacity(0.30)
              //           : Colors.transparent,
              //       child: DrawerListTile(
              //         title: "Gestion de compte",
              //         svgSrc: "assets/icons/manage_accounts.svg",
              //         press: () {
              //           controller.screenIndex = 4;
              //         },
              //       )),
              // if (authController.currentAccount!.role == "admin")
              //   Container(
              //       color: (controller.screenIndex == 5)
              //           ? bgColor.withOpacity(0.30)
              //           : Colors.transparent,
              //       child: DrawerListTile(
              //         title: "Gestion de stations",
              //         svgSrc: "assets/icons/settings_input_antenna.svg",
              //         press: () {
              //           controller.screenIndex = 5;
              //         },
              //       )),
            ],
          ),
        ));
  }
}

class DrawerListTile extends StatelessWidget {
  const DrawerListTile({
    Key? key,
    // For selecting those three line once press "Command+D"
    required this.title,
    required this.svgSrc,
    required this.press,
  }) : super(key: key);

  final String title, svgSrc;
  final VoidCallback press;

  @override
  Widget build(BuildContext context) {
    return ListTile(
      onTap: press,
      horizontalTitleGap: 0.0,
      leading: SvgPicture.asset(
        svgSrc,
        color: Colors.white,
        height: 16,
      ),
      title: Text(
        title,
        style: GoogleFonts.poppins(
          textStyle: const TextStyle(
              color: Colors.white70, fontWeight: FontWeight.w500, fontSize: 16),
        ),
      ),
    );
  }
}
