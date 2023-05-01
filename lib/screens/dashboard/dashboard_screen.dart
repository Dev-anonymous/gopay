import 'package:google_fonts/google_fonts.dart';
import 'package:gopay/controllers/auth_controller.dart';
import 'package:gopay/screens/dashboard/components/realtime_fl_ch.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../constants.dart';
import '../../controllers/solde_controller.dart';
import '../../responsive.dart';
import '../../widgets/header.dart';
import 'components/recent_transactions.dart';
import 'components/recent_transferts.dart';

class DashboardScreen extends StatefulWidget {
  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  // AuthController _authController = Get.put(AuthController());

  String? station;

  @override
  Widget build(BuildContext context) {
    SoldeController sC = Get.put(SoldeController());
    return SafeArea(
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(defaultPadding),
        child: Column(
          children: [
            Header(),
            const SizedBox(height: defaultPadding),
            Divider(
              thickness: 1,
              color: primaryColor.withOpacity(0.6),
            ),
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  flex: 5,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Expanded(
                            child: Text(
                              "Soldes du compte",
                              style: GoogleFonts.poppins(
                                textStyle: const TextStyle(
                                    color: Colors.black38,
                                    fontWeight: FontWeight.w500,
                                    fontSize: 20),
                              ),
                            ),
                          ),
                          Container(
                            clipBehavior: Clip.hardEdge,
                            decoration: BoxDecoration(
                                color: primaryColor,
                                borderRadius: BorderRadius.circular(8)),
                            child: Padding(
                              padding: const EdgeInsets.all(8.0),
                              child: InkWell(
                                onTap: () {
                                  sC.init();
                                },
                                child: const Icon(
                                  Icons.refresh,
                                  color: Colors.white,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                      Divider(
                        thickness: 1,
                        color: primaryColor.withOpacity(0.6),
                      ),
                      // if (Responsive.isMobile(context))
                      // _buildSelectStationWidget(),
                      const SizedBox(height: defaultPadding),
                      if (Responsive.isMobile(context))
                        Obx(
                          () => !sC.isSoldeUSDLoaded
                              ? soldeLoadingWidget()
                              : _buildSoldInfo(
                                  context, 'Numero de compte', sC.numeroCompte),
                        ),

                      const SizedBox(height: defaultPadding),
                      Row(
                        children: [
                          if (!Responsive.isMobile(context))
                            Expanded(
                              child: Obx(
                                () => !sC.isSoldeUSDLoaded
                                    ? soldeLoadingWidget()
                                    : _buildSoldInfo(context,
                                        'Numero de compte', sC.numeroCompte),
                              ),
                            ),
                          if (!Responsive.isMobile(context))
                            const SizedBox(width: defaultPadding),

                          /// Desktop
                          if (!Responsive.isMobile(context))
                            Obx(
                              () => !sC.isSoldeUSDLoaded
                                  ? soldeLoadingWidget()
                                  : _buildSoldInfo(context, 'USD', sC.soldeUSD),
                            ),

                          /// Mobile
                          if (Responsive.isMobile(context))
                            Expanded(
                              child: Obx(
                                () => !sC.isSoldeUSDLoaded
                                    ? soldeLoadingWidget()
                                    : _buildSoldInfo(
                                        context, 'USD', sC.soldeUSD),
                              ),
                            ),

                          const SizedBox(width: defaultPadding),

                          /// Desktop
                          if (!Responsive.isMobile(context))
                            Obx(
                              () => !sC.isSoldeCDFLoaded
                                  ? soldeLoadingWidget()
                                  : _buildSoldInfo(context, 'CDF', sC.soldeCDF),
                            ),

                          /// Mobile
                          if (Responsive.isMobile(context))
                            Expanded(
                                child: Obx(
                              () => !sC.isSoldeCDFLoaded
                                  ? soldeLoadingWidget()
                                  : _buildSoldInfo(context, 'CDF', sC.soldeCDF),
                            ))
                        ],
                      ),
                      const SizedBox(height: 32),
                      Text(
                        "Mes transactions",
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.black38,
                              fontWeight: FontWeight.w500,
                              fontSize: 20),
                        ),
                      ),
                      Divider(
                        thickness: 1,
                        color: primaryColor.withOpacity(0.6),
                      ),
                      const SizedBox(height: defaultPadding),
                      const RecentTransactions(),
                      const SizedBox(height: 32),
                      Text(
                        "Mes demandes de transferts",
                        style: GoogleFonts.poppins(
                          textStyle: const TextStyle(
                              color: Colors.black38,
                              fontWeight: FontWeight.w500,
                              fontSize: 20),
                        ),
                      ),
                      Divider(
                        thickness: 1,
                        color: primaryColor.withOpacity(0.6),
                      ),
                      const SizedBox(height: defaultPadding),
                      const SizedBox(height: 300, child: RecentTransferts()),
                      // if (Responsive.isMobile(context))
                      //   SizedBox(height: defaultPadding),
                      // if (Responsive.isMobile(context)) StarageDetails(),
                    ],
                  ),
                ),
                if (!Responsive.isMobile(context))
                  SizedBox(width: defaultPadding),
                // On Mobile means if the screen is less than 850 we dont want to show it
                // if (!Responsive.isMobile(context))
                //   Expanded(
                //     flex: 2,
                //     // child: StarageDetails(),
                //     child: Container(
                //       decoration: BoxDecoration(
                //         color: secondaryColor,
                //         borderRadius:
                //             const BorderRadius.all(Radius.circular(10)),
                //       ),
                //     ),
                //   ),
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget soldeLoadingWidget() {
    return Container(
        height: 130,
        padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
        decoration: const BoxDecoration(
          color: primaryColor,
          borderRadius: BorderRadius.all(Radius.circular(10)),
        ),
        child: const Center(
          child: CircularProgressIndicator(
            color: bgColor,
          ),
        ));
  }

  Widget _buildSoldInfo(BuildContext context, String currency, String solde) {
    return Container(
        // width: double.infinity,
        padding:
            const EdgeInsets.only(top: 8, left: 1.5, right: 1.5, bottom: 1.5),
        decoration: const BoxDecoration(
          color: primaryColor,
          borderRadius: BorderRadius.all(Radius.circular(10)),
        ),
        child: Container(
          decoration: const BoxDecoration(
            color: bgColor,
            borderRadius: BorderRadius.all(Radius.circular(10)),
          ),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  currency,
                  style: GoogleFonts.poppins(
                    textStyle: const TextStyle(
                        color: Colors.black,
                        fontWeight: FontWeight.w500,
                        fontSize: 20),
                  ),
                ),
                Align(
                  alignment: Alignment.bottomRight,
                  child: Text(
                    solde,
                    style: GoogleFonts.poppins(
                      textStyle: const TextStyle(
                          color: Colors.black,
                          fontWeight: FontWeight.w500,
                          fontSize: 32),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ));
  }
}
