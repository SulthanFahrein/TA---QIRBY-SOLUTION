import 'package:flutter/material.dart';
import 'package:test_ta_1/ui_component/widget/button_component/default_button.dart';
import 'package:test_ta_1/core/config/app_asset.dart';
import 'package:test_ta_1/core/config/app_route.dart';

class IntroPage extends StatelessWidget {
  const IntroPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        fit: StackFit.expand,
        children: [
          Image.asset(
            AppAsset.bgIntro,
            fit: BoxFit.cover,
          ),
          Container(
            height: MediaQuery.of(context).size.height * 0.4,
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.bottomCenter,
                end: Alignment.topCenter,
                colors: [
                  Colors.black,
                  Colors.transparent,
                ],
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 0, 16, 40),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.end,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Book your dream home.\nStarts Here',
                  style: Theme.of(context).textTheme.headlineLarge!.copyWith(
                        color: Colors.white,
                        fontWeight: FontWeight.w900,
                      ),
                ),
                const SizedBox(height: 8),
                Text(
                  'More than just a house',
                  style: Theme.of(context).textTheme.titleMedium!.copyWith(
                        color: Colors.white,
                        fontWeight: FontWeight.w900,
                      ),
                ),
                const SizedBox(height: 30),
                DefaultButton(
                  label: "Get Started",
                  onTap: () {
                    Navigator.pushReplacementNamed(context, AppRoute.signin);
                  },
                  isExpand: true,
                )
              ],
            ),
          ),
        ],
      ),
    );
  }
}
