import 'package:flutter/material.dart';
import 'package:test_ta_1/core/config/app_color.dart';
import 'package:test_ta_1/core/config/app_route.dart';
import 'package:test_ta_1/ui_component/widget/button_component/icon_button.dart';
import 'package:test_ta_1/ui_component/widget/maps_component/map_content.dart';

class MapsPage extends StatelessWidget {
  const MapsPage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        const SizedBox(height: 24),
        searchField(context),
        const SizedBox(height: 20),
        MapContent(),
      ],
    );
  }

  Widget searchField(context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      child: Row(
        children: [
          Expanded(
            child: SizedBox(
              height: 45,
              child: Stack(
                children: [
                  TextButton(
                    onPressed: () {
                      Navigator.pushNamed(context, AppRoute.search);
                    },
                    style: ButtonStyle(
                      backgroundColor: MaterialStateProperty.all<Color>(
                          Colors.white), // Warna latar belakang
                      shape: MaterialStateProperty.all<RoundedRectangleBorder>(
                        RoundedRectangleBorder(
                          borderRadius:
                              BorderRadius.circular(30.0), // Sudut tombol
                          side: BorderSide(
                              color: Colors.grey.shade400), // Garis pinggir
                        ),
                      ),
                    ),
                    child: const Padding(
                      padding:
                          EdgeInsets.symmetric(horizontal: 0.0, vertical: 0.0),
                      child: Row(
                        children: [
                          Icon(Icons.search,
                              color: Colors.grey), // Icon pencarian (opsional)
                          SizedBox(width: 8.0),
                          Text(
                            'Search',
                            style:
                                TextStyle(color: Colors.grey, fontSize: 14.0),
                          ),
                        ],
                      ),
                    ),
                  ),
                  Align(
                    alignment: Alignment.centerRight,
                    child: IconButtonCustom(
                      color: AppColor.secondary,
                      icon: Icons.search,
                    ),
                  )
                ],
              ),
            ),
          ),
          const SizedBox(width: 8),
          IconButtonCustom(
            color: AppColor.primary,
            icon: Icons.filter_list,
            onTap: () {
              Navigator.pushNamed(context, AppRoute.filter);
            },
          ),
          const SizedBox(width: 8),
          
        ],
      ),
    );
  }
}
