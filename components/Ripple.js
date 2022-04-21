import React from 'react'
import { StyleSheet, Text, View } from 'react-native'
import { GestureHandlerRootView, TapGestureHandler, } from 'react-native-gesture-handler'
import Animated, {measure, runOnJS, useAnimatedGestureHandler, useAnimatedRef, useAnimatedStyle, useSharedValue, withTiming} from 'react-native-reanimated'

const Ripple = (props) => {

    const centerX = useSharedValue(0);
    const centerY = useSharedValue(0);
    const scale = useSharedValue(0);

    const aRef = useAnimatedRef();
    const width = useSharedValue(0);
    const height = useSharedValue(0);

    const rippleOpacity = useSharedValue(0);

    const tapGestureEvent = useAnimatedGestureHandler({
        onStart : (tapEvent) => {

            const layout = measure(aRef);
            //console.log(layout);
            width.value = layout.width;
            height.value = layout.height;
            
            centerX.value = tapEvent.x;
            centerY.value = tapEvent.y;

            rippleOpacity.value = 1;

            scale.value = 0;
            scale.value = withTiming(1, {duration : 1000});
        },
        onActive : (event) => {
            //console.log(event);
            if(props.onTap()){
                props.onTap();
            }
        },
        onFinish : () => {
            rippleOpacity.value = withTiming(0);
        }
    });

    const animatedStyle = useAnimatedStyle(() => {

        const circleRadius = Math.sqrt(width.value ** 2 + height.value ** 2);

        const translateX = centerX.value - circleRadius;
        const translateY = centerY.value - circleRadius;

        return {
            width : circleRadius * 2,
            height : circleRadius * 2,
            borderRadius : circleRadius,
            opacity: rippleOpacity.value,
            backgroundColor : 'rgba(0,0,0,0.2)',
            position : 'absolute',
            top : 0,
            left : 0,
            transform : [
                {translateX},
                {translateY},
                {scale : scale.value}
            ]
        }
    });

    return (
        <View >
            <GestureHandlerRootView>
                <TapGestureHandler onGestureEvent={tapGestureEvent}>
                    <Animated.View style={[props.style, {overflow : 'hidden'} ]} ref={aRef}>
                        <View style={{position:'absolute'}}>{props.children}</View>
                        <Animated.View style={animatedStyle} />
                    </Animated.View>
                </TapGestureHandler>
            </GestureHandlerRootView>
        </View>
        
        
    )
}

export default Ripple

const styles = StyleSheet.create({})