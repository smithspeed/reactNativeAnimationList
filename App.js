import React from 'react'
import { View, Text, StyleSheet } from 'react-native'

import BasicIntro from './animationList/Basic'; //Animation Introduction Details
import PanGestureHandlerScreen from './animationList/PanGestureHandler'; // PanGestureHandler
import InterpolateWithScrollView  from './animationList/InterpolateWithScrollView'; 
import InterpolateWithColor from './animationList/InterpolateWithColor';
import PinchGestureHandlerScreen from './animationList/PinchGestureHandler';
import DoubleTap from './animationList/DoubleTap';
import ColorPickerScreen from './animationList/ColorPickerScreen';
import CircularProgressBar from './animationList/CircularProgressBar';
import SwipeToDelete from './animationList/SwipeToDelete';
import RippleEffect from './animationList/RippleEffect';
import PerspectiveMenu from './animationList/PerspectiveMenu';
import SlidingCounterScreen from './animationList/SlidingCounterScreen';
import ClockLoader from './animationList/ClockLoader';
import MagicLayout from './animationList/MagicLayout';
import BottomSheetScreen from './animationList/BottomSheetScreen';

export default function App() {

    
    return (
        <View style={styles.container}>
            <BottomSheetScreen/>
        </View>
    )
}

const styles = StyleSheet.create({
    container : {
        flex :1,
        backgroundColor : 'white',
        
    }
});
